const MongoClient = require('mongodb').MongoClient;
const url = "mongodb+srv://dbUser1:dbuser123@cluster0.rjmq5.mongodb.net/?retryWrites=true&w=majority";

  MongoClient.connect(url, async function(err, db) {
    if(err) { return console.log(err); }
  
    var dbo = db.db("library");
	  var collection = dbo.collection('books');
	
	  var theQuery = { title: /^Who/ };
    await collection.deleteMany(theQuery, function(err, obj) {
      if (err) console.log(err);
      else console.log("document(s) deleted");
    }); //end delete
    db.close();

  });  // end connect



