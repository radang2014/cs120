const MongoClient = require('mongodb').MongoClient;
const url = "mongodb+srv://dbUser1:dbuser123@cluster0.rjmq5.mongodb.net/?retryWrites=true&w=majority";

client =new MongoClient(url);
async function findit () {
  try {
    await client.connect();
    var dbo = client.db("library");
	  var coll = dbo.collection('books');
    const options = {
       sort: { author: 1 },
       projection: { _id: 0, title: 1, author: 1 },
    };
   
   /* const result = coll.find({},options);
    // print a message if no documents were found
    if ((result.countDocuments) === 0) {
      console.log("No documents found!");
    }
    
	 await result.forEach(function(item){    
		  console.log(item.title);
	  });
    */

    theQuery = {author:"Bob Smith"}
    await coll.find(theQuery,{title:1}).toArray(function(err, items) {
      if (err) {
        console.log("Error: " + err);
      } 
      else 
      {
        console.log("Items: ");
        for (i=0; i<items.length; i++)
          console.log(i + ": " + items[i].title )//+ " by: " + items[i].author);				
      }   
      
    });  //end find		
  } 
  catch(err) {
	  console.log("Database error: " + err);
}
  finally {
    client.close();
  }
}  //end findit
findit();//.catch(console.dir);



