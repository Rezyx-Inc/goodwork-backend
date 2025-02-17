const fs = require('fs'); //To read and/or write fies
const path = require('path'); // To work with file paths and directories
const http = require('http'); // To work with HTTP servers
const open = require('open'); // Automatically open the browser
const { google } = require('googleapis');

const SCOPES = ['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/spreadsheets'];
const TOKEN_PATH = path.join(__dirname, 'token.json');
const CREDENTIALS_PATH = path.join(__dirname, 'credentials.json');

async function authorize() {
  try {

    //Check and throw error if path already exists
    if (!fs.existsSync(CREDENTIALS_PATH)) {
      throw new Error(`Credentials file not found at ${CREDENTIALS_PATH}`);
    }

    //Read and parse the credentials
    const credentials = JSON.parse(fs.readFileSync(CREDENTIALS_PATH, 'utf-8'));
    const { client_secret, client_id, redirect_uris } = credentials.installed;
    const oAuth2Client = new google.auth.OAuth2(client_id, client_secret, redirect_uris[0]);

    if (fs.existsSync(TOKEN_PATH)) {
      const token = JSON.parse(fs.readFileSync(TOKEN_PATH, 'utf-8'));
      oAuth2Client.setCredentials(token);

      //Check and return if the token is still valid
      if (token.expiry_date && token.expiry_date > Date.now()) {
        console.log('Valid token found. Using it.');
        return oAuth2Client;
      }
      console.log('Token expired. Refreshing...'); //Refresh and get a new token
    }

    return await getNewToken(oAuth2Client);
  } catch (error) {

    //Log the error in authorization
    console.error('Authorization error:', error.message);
    throw error;
  }
}

//Function to get a new access token
async function getNewToken(oAuth2Client) {
  const authUrl = oAuth2Client.generateAuthUrl({
    access_type: 'offline',
    scope: SCOPES,
  });

  console.log('Opening browser for authorization...');
  await open(authUrl);

  //Create new http server and get the token
  return new Promise((resolve, reject) => {
    const server = http.createServer(async (req, res) => {
      if (req.url.startsWith('/?code=')) {
        const query = new URL(req.url, 'http://localhost').searchParams;
        const code = query.get('code');

        res.end('Authorization successful! You can close this tab.');
        server.close();

        try {

          //Read and set the auth credentials
          const { tokens } = await oAuth2Client.getToken(code);
          oAuth2Client.setCredentials(tokens);

          fs.writeFileSync(TOKEN_PATH, JSON.stringify(tokens));
          console.log('Token saved to', TOKEN_PATH);
          resolve(oAuth2Client);
        } catch (err) {

          //Log the error message in case of failure
          console.error('Error retrieving access token:', err.message);
          reject(err);
        }
      } else {
        res.end('Invalid request.');
      }
    });

    //Server listens to port 80
    server.listen(80, () => console.log('Waiting for authorization response...'));
  });
}

module.exports = { authorize };
