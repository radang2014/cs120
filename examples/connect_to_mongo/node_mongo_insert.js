const MongoClient = require('mongodb').MongoClient;
const url = "mongodb+srv://dbUser1:dbuser123@cluster0.rjmq5.mongodb.net/?retryWrites=true&w=majority";

function main() 
{
  MongoClient.connect(url, function(err, db) {
  if(err) { return console.log(err); }
  
    var dbo = db.db("library");
	  var collection = dbo.collection('books');
	
	  var newData = {"title": "Who Ate the Cheese", "author": "Fin Haddie"};
	  collection.insertOne(newData, function(err, res) {
       if (err) { return console.log(err); }
       console.log("new document inserted");
	});
});
}

main();


