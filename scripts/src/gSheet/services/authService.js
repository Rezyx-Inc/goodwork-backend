const { google } = require('googleapis');
const path = require('path');

const SCOPES = [
  'https://www.googleapis.com/auth/spreadsheets',
  'https://www.googleapis.com/auth/drive.file',
  'https://www.googleapis.com/auth/drive',
  'https://www.googleapis.com/auth/drive.metadata'
];


const CLIENT_ID = '87009881002-8a43qvn1q9c588sd7ae7ki6m9ud41tpi.apps.googleusercontent.com';
const CLIENT_SECRET = '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';
const REDIRECT_URI = 'http://localhost:3000/oauth2callback';

const SERVICE_FILE = path.join(__dirname, '../credentials.json');

async function authorize() {
  try {

    const auth = new google.auth.GoogleAuth({
      scopes: SCOPES,
      keyFile: SERVICE_FILE
    });

    const token = await auth.getAccessToken();

    return token

  } catch (e) {
    console.log(e)
  }
}

module.exports = { authorize };
