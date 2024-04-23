/* For server setup */
var http = require('http');
var port = process.env.PORT || 8080;

/* For connection to mongodb */
var mongo = require('mongodb');
const MongoClient = mongo.MongoClient;
const conn_str = process.env.MONGODB_URI || 
                 "mongodb+srv://superuser:dbuser123@cs120psets.gunpm1n.mongodb.net/?retryWrites=true&w=majority&appName=cs120psets";

/* Data / files to read from */
var fs = require('fs');
const ZIPS_PATH = "data/zips.csv";
const HOMEPAGE_PATH = "index.html";

/* For parsing query string */
var url = require('url');

/* Logic for server creation and processing request */
http.createServer(function(req, res) {
    var urlObj = url.parse(req.url, true);

    /* App homepage */
    if (urlObj.pathname == "/") {
        fs.readFile(HOMEPAGE_PATH, function(err, txt) {
            if (err) {
                console.log("Error: Failed to read from " + HOMEPAGE_PATH + ": " + err);
            } else {
                res.write(txt);
            }
            res.end();
        });
        return;
    }

    /* If requesting video demo, respond with video */
    if (urlObj.pathname == "/app1/demo") {
        res.writeHead(206, {'Content-Type': 'video/mp4'});
        var video_stream = fs.createReadStream("app1_files/app1_demo.mp4");
        video_stream.pipe(res);

        return;
    }

    res.write("<p>Print Statement 1</p>");

    fs.readFile("css/style.css", function(err, txt) {
        if (err) {
            console.log("Error reading from file css/style.css: " + err);
        } else {
            res.write("<p>Print Statement 2</p>");
            /* Boilerplate HTML */
            res.write("<!DOCTYPE html>");
            res.write("<style type=\"text/css\">");
            if (err) {
                console.log("Error: Failed to read from css/style.css: " + err);
            } else {
                res.write(txt);
            }
            res.write("</style>");

            /* Connect to Mongo Database */
            MongoClient.connect(conn_str, async function(err, db) {
                res.write("<p>Print Statement 3</p>");
                if (err) {
                    console.log("Error connecting to database: " + err);
                } else {
                    try {
                        /* App 1 Page */
                        if (urlObj.pathname.startsWith("/app1")) {
                            res.write("<p>Print Statement 4</p>");
                            await write_app1(req, res, db);
                        }

                        /* App 2 Page */
                        if (urlObj.pathname.startsWith("/app2")) {
                            await write_app2(req, res, db);
                        }
                    } catch (err) {
                        console.log("Database error: " + err);
                    } finally {
                        db.close();
                        res.end();
                    }
                }
            });
        }
    });
}).listen(port);

/* Writes contents of Web App 1, using database handle `db` */
async function write_app1(req, res, db) {
    var urlObj = url.parse(req.url, true);

    /* Write info about how to use the app */
    res.write("<h1>Problem Set 3-4</h1>");
    res.write("<p>Welcome to App 1!</p>");
    res.write("<p>To insert the contents of <code>zips.csv</code> to the database, append " + 
              "<code>?mode=insert</code> to the URL.</p>");
    res.write("<p>To clear the database of all zip codes, append <code>?mode=delete</code> " + 
              "to the URL.</p>");
    
    /* Get database related handles */
    var dbo = db.db("ps3-4");
    var places = dbo.collection("places");
    
    /* Write mode dependent functionality */
    var query = urlObj.query;
    var mode = query.mode;
    if (mode == "insert") {
        /* Read from CSV file */
        var zips_txt = await fs.promises.readFile(ZIPS_PATH, "utf8");
        for (zips_entry of zips_txt.split("\n")) {
            zips_entry = zips_entry.trim(); // trim off any whitespace, e.g. trailing \r character
            await insert_into_db(req, res, zips_entry, places);
        }
        res.write("<p><strong>All items in <code>zips.csv</code> successfully inserted into database!</strong></p>");
    } else if (mode == "delete") {
        /* Empty collection */ 
        places.deleteMany({});
        res.write("<p><strong>All entries in places collection successfully deleted!</strong></p>");
    } else if (mode != null) {
        res.write("<p class=\"error\">ERROR: Your selected mode was neither <code>insert</code> nor <code>delete</code>. " + 
                  "Please fix your query string.</p>");
    } else {
        /* Embed demo video if no mode specified */
        res.write("<p><a href=\"app1/demo\">Video demo of Web App 1 working</a></p>");
    }

    /* Write footer */
    res.write("<hr />");
    res.write("<a href=\"/\">Return to Problem Set Landing Page</a>");
}

/* Inserts `zip_entry` of format "Location,ZipCode" into database collection `places` */
async function insert_into_db(req, res, zip_entry, places) {
    zip_entry = zip_entry.split(",");
    if (zip_entry.length != 2) { /* Skip invalid rows */
        return;
    }
    location = zip_entry[0].trim();
    zip_code = zip_entry[1].trim();

    /* Attempt to find location in database */
    var find_res = await places.find({place: location}).toArray();
    if (find_res.length > 1) { /* Error checking: this should never happen */
        res.write("<p class=\"error\">IMPOSSIBLE ERROR: Database contained multiple entries with " + 
                  "the same location. Please contact developer.</p>");
    } else if (find_res.length == 0) {
        /* If location does not exist yet in database, create a new document */
        await places.insertOne({place: location, zips: [zip_code]});
        console.log("Inserted new location " + location + " to database with zip code " + zip_code + ".");
    } else {
        /* If location already exists in database, update existing entry to include additional zip code */
        find_res = find_res[0];
        var curr_zips = find_res.zips;
        if (!curr_zips.includes(zip_code)) {
            const update_document = {
                $push : {
                    zips : zip_code
                }
            }
            await places.updateOne({place: location}, update_document);
        }
        console.log("Updated location " + location + " to also contain the zip code " + zip_code + ".");
    }
}

/* Writes contents of Web App 2, using database handle `db` */
async function write_app2(req, res, db) {
    var urlObj = url.parse(req.url, true);

    if (urlObj.pathname == "/app2/home") {
        /* Implementation of "home" view functionality */
        var home_contents = await fs.promises.readFile("app2_files/home.html");
        res.write(home_contents);
        return;
    } else if (urlObj.pathname == "/app2/process") {
        /* Get database related handles */
        var dbo = db.db("ps3-4");
        var places = dbo.collection("places");

        res.write("<h1>Problem Set 3-4</h1>");

        /* Check that database isn't empty */
        all_data = await places.find({}).toArray();
        if (all_data.length == 0) {
            res.write("<p class=\"error\">ERROR: Database is currently empty. Please populate by " + 
                      "running App 1 in insert mode.</p>");
        } else {
            /* Implementation of "process" view functionality */
            await write_queried_info(req, res, places, urlObj.query.lookup_key);
        }
        res.write("<p><a href=\"home\">Look Up Another Location</a></p>");
    } else {
        res.write("<h1>Problem Set 3-4</h1>");
        res.write("<p class=\"error\">ERROR: " + urlObj.pathname + " is not a valid view of this " + 
                  "app. Please double check that your URL is correct.</p>");
    }
    
    /* Write footer */
    res.write("<hr />");
    res.write("<a href=\"/\">Return to Problem Set Landing Page</a>");
}

/* 
 * Write result of querying database collection `places` onto page, looking for all information about a particular place 
 * based on `lookup_key`, which could be the place name or zip code 
 */
async function write_queried_info(req, res, places, lookup_key) {
    /* Determine if lookup_key is zip code based on if first digit is numeric */
    var is_zip_code = (lookup_key.length > 0) && (lookup_key.charAt(0) >= '0' && lookup_key.charAt(0) <= '9');

    /* Query database based on lookup key */
    if (is_zip_code) {
        res.write("<p>The zip code you entered was: " + lookup_key + "</p>");
        var find_res = await places.find({zips : lookup_key}).toArray();
    } else {
        res.write("<p>The location you entered was: " + lookup_key + "</p>");
        var find_res = await places.find({place: lookup_key}).toArray();
    }

    if (find_res.length == 0) {
        /* In this case, the user entered info incorrectly */
        res.write("<p class=\"error\">ERROR: The location or zip code you entered was either invalid or not " + 
                  "in the database. Please try again with a different query.</p>");
    } else {
        /* In this case, there was a match! */
        res.write("<div class=\"box\">");
        res.write("<strong>Here is the info associated with what you entered:</strong><br /><br />");
        var first_iter = true;
        for (entry of find_res) { /* There may have been multiple matches */
            if (!first_iter) {
                res.write("<br /><br />"); /* add line breaks */
            }
            res.write("Place name: " + entry.place + "<br />");
            res.write("Zip codes: " + entry.zips.join(", "));
            first_iter = false;
        }
        res.write("</div>");
    }
}


