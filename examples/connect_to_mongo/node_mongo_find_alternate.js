const MongoClient = require('mongodb').MongoClient;
const url = "mongodb+srv://dbUser1:dbUserpw@cluster0.rjmq5.mongodb.net/?retryWrites=true&w=majority";  // use your credentials!


client = new MongoClient(url);
async function doit() {
  try {
    await client.connect();
    var dbo = client.db("library");
	var coll = dbo.collection('books');
    const options = {
       sort: { author: 1 },
       projection: { _id: 0, title: 1, author: 1 },
    };
    const curs = coll.find({},options);
    // print a message if no documents were found
    if ((await curs.count()) === 0) {
      console.log("No documents found!");
    }
    
	  await curs.forEach(function(item){
		  console.log(item.title);
	  });
  } 
  catch(err) {
	  console.log("Database error: " + err);
}
  finally {
    client.close();
  }
}  //end doit
doit();//.catch(console.dir);



