const express = require('express');
const cors = require('cors-express-middleware');
const path = require('path');
const app = express();

app.get('/', function(req, res) { res.sendFile(path.join(__dirname, '/index.html'));});
app.use(cors());
app.listen(3000, () => { console.log('Server is running on port 3000'); });