const client = require('mongodb').MongoClient;
const connStr = "mongodb+srv://superuser:dbuser123@cs120psets.gunpm1n.mongodb.net/?retryWrites=true&w=majority&appName=cs120psets";

// pass in connection string and a callback function
// which takes in error indicator and the database
console.log('hey');
client.connect(connStr, function(err, mydb) {
    if (err) { console.log(err); }
    else {
        var dbo = mydb.db("library"); // dbo is short for db object
	var collection = dbo.collection('songs'); // get the collection
	console.log("Success!");
	mydb.close();
    }
});

