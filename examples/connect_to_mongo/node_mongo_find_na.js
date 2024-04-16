const MongoClient = require('mongodb').MongoClient;
const url = "mongodb+srv://dbUser1:dbuser123@cluster0.rjmq5.mongodb.net/?retryWrites=true&w=majority";

  MongoClient.connect(url, function(err, db) {
    if(err) { 
		console.log("Connection err: " + err); return; 
	}
  
    var dbo = db.db("library");
	var coll = dbo.collection('books');
	
	theQuery = {author:"Bob Smith"}
	result = coll.find(theQuery,{title:1})
	result.toArray(function(err, items) {

	  if (err) {
		console.log("Error: " + err);
	  } 
	  else 
	  {
		console.log("Items: ");
		for (i=0; i<items.length; i++)
			console.log(i + ": " + items[i].title )//+ " by: " + items[i].author);				
	  }   
	  db.close();
	});  //end find		
});  //end connect





