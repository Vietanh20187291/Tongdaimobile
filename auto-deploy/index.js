const express = require('express')
const app = express()
var cp = require('child_process');
const port = 3000

// respond with "hello world" when a GET request is made to the homepage
app.post('/webhook', (req, res) => {
	console.log(req.body)
	cp.exec('./auto_pull.sh', function(err, stdout, stderr) {
		// handle err, stdout, stderr
		console.log(stdout)
		console.log(stderr) 
	});
	res.send('hello world')
})
app.listen(port, () => {
  console.log(`Auto pull app listening on port ${port}`)
})
