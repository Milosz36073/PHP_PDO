<?php 

	// initialization data to connect with database
	$host = 'localhost';
	$user = 'root';
	$password = '123456';
	$dbname = 'pdoposts';

	//set DSN - a string with assosiated data structure to describe connection with datasource
	// so here we include whatever driver, database we wanna use, host, database name itd
	$dsn='mysql:host='. $host .';dbname='.$dbname;

	// create a PDO instance
	$pdo = new PDO($dsn, $user, $password);
	//set default fecht method
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	// query we use when we don't need to use any variables or user data
	// prepared statment we use when we want use user data

	# PDO QUERY

	$stmt = $pdo->query('SELECT * FROM posts');

	//result as array assoc
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		echo $row['title'] . '<br>';
	}

	// result like a object
	while($row = $stmt->fetch()){
		echo $row->title . '<br>';
	}

	#PREPARED STATMENTS (prepare & execute)
	// unsafe : here in variable $author somebody can put a sql query, that why it isn't safe
	// $sql = "SELECT * FROM posts WHERE author = '$author'"

	//safe
	// using prepared statment you can use a position parameters or named parameters
	//position parametrs are working with msqli

	// User input
	$author = 'Brad';
	$is_published = true;
	$id=1;
	$limit=1;
	
	//positional params
	$sql = "SELECT * FROM posts WHERE author= ? && is_published = ? LIMIT ?"; //question mark is a placeholder
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$author, $is_published, $limit]); //order is sensitive, first variable should be first in this array
	$posts = $stmt->fetchAll();

	// Named params
	$sql = "SELECT * FROM posts WHERE author= :author && is_published = :is_published"; 
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['author'=>$author, 'is_published' => $is_published]); 
	$posts = $stmt->fetchAll();

	foreach($posts as $post){
		echo $post->title . '<br>';
	}

	#FETCH SINGLE POST
	$sql = "SELECT * FROM posts WHERE id= :id"; 
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id'=>$id]);
	$post = $stmt->fetch();

 	//GET ROW COUNT

	$stmt = $pdo->prepare('SELECT * FROM posts WHERE author = ?');
	$stmt->execute([$author]);
	$postCount = $stmt->rowCount();
	echo $postCount;
 

//INSERTING DATA

	$title = 'Post Five';
	$body = 'This is post five';
	$author = 'Kevin';

	$sql = 'INSERT INTO posts(title, body, author) VALUES (?, ?, ?)';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(([$title, $body, $author]));
	echo 'post aded';

// UPDATE DATA
	$id = 1;
	$body = 'This post was updated';

	$sql = 'UPDATE posts SET body= :body WHERE id= :id';
	$stmt = $pdo->prepare($sql);
	$stmt->execute((['body'=>$body, 'id'=>$id ]));
	echo 'post updated';

//DELETE
	$id = 3;

	$sql = 'DELETE FROM posts WHERE id= :id';
	$stmt = $pdo->prepare($sql);
	$stmt->execute((['id'=>$id ]));
	echo 'post deleted';

//SEARCH
	$search = "%f%";
	$sql = 'SELECT * FROM posts WHERE title LIKE ?';
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$search]);
	$posts = $stmt->fetchAll();

// foreach($posts as $post){
// 	echo $post->title . '<br>';
// }

