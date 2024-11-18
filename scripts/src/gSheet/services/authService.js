const { google } = require('googleapis');
const puppeteer = require('puppeteer');
const fs = require('fs');
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

const e_mail = 'goodwork.world.gsheet@gmail.com';
const pass_word = 'kubkot-mebgaf-wiKfu8';

const OAUTH2_CLIENT = new google.auth.OAuth2(CLIENT_ID, CLIENT_SECRET, REDIRECT_URI);

// Function to get an OAuth token using Puppeteer
async function getOAuthTokenWithPuppeteer() {
  const authUrl = OAUTH2_CLIENT.generateAuthUrl({
    access_type: 'offline',
    scope: SCOPES,
  });

  const browser = await puppeteer.launch({ headless: false });
  const [page] = await browser.pages();

  // Navigate to the authorization URL
  await page.goto(authUrl);

  // Wait for the email input field and type the email
  await page.waitForSelector('input[name="identifier"]');
  await page.type('input[name="identifier"]', e_mail);
  await page.click('#identifierNext');

  // Wait for the password input field and type the password (with increased timeout)
  try {
    console.log("Waiting for password field...");
    await new Promise(function (resolve) { setTimeout(resolve, 6000) });
    await page.waitForSelector('input[name="Passwd"]');
    //await page.type('input[name="Passwd"]', pass_word);
    await page.type('div[id="password"]>>>input[name="Passwd"]', pass_word);
  } catch (error) {
    console.log('Error waiting for password field:', error);
    const pageContent = await page.content();
    //console.log(pageContent); // Log the HTML content for debugging
    await page.screenshot({ path: 'error_screenshot.png' }); // Take a screenshot for debugging
    throw error;
  }

  // Click the "Next" button after entering the password
  try {
    console.log("Waiting for password next button...");
    await page.waitForSelector('#passwordNext', { visible: true, timeout: 60000 }); // Wait for the button to be visible
    await page.click('div[id=passwordNext]>>>button');
  } catch (error) {
    console.log('Error clicking password next button:', error);
    const pageContent = await page.content();
    console.log(pageContent); // Log the HTML content for debugging
    await page.screenshot({ path: 'error_screenshot_password_next.png' }); // Take a screenshot for debugging
    throw error;
  }

  try {
    console.log("Waiting for consent button...");
    await new Promise(function (resolve) { setTimeout(resolve, 6000) });
    await page.click('div[jsname="QkNstf"]>>>button');
  } catch {
    console.log("Error clicking consent button");
  }

  var requestUrls = [];
  //Storing the request urls in array before continue button is pressed
  page.on('request', request => {
    requestUrls.push(request.url())
  });

  try {
    console.log("Waiting for select button...");
    await new Promise(function (resolve) { setTimeout(resolve, 6000) });
    await page.click('input[id="i1"]');
    await new Promise(function (resolve) { setTimeout(resolve, 6000) });
    await page.click('div[jsname="uRHG6"]>>>button');
  } catch {
    console.log("Error clicking consent button");
  }

  // Wait for navigation and capture the URL with the code
  await page.waitForNavigation({ waitUntil: 'load', timeout: 60000 }); // Ensure navigation completes

  //Filtering the request URLs for the callback URL I'm looking for
  const callBackUrl = requestUrls.filter(element => {
    if (element.includes(`${REDIRECT_URI}`)) {
      console.log(element);
      return element;
    }
  });

  const currentUrl = callBackUrl[0];
  const code = new URL(currentUrl).searchParams.get('code');

  // Close the browser
  //await browser.close();

  if (!code) {
    throw new Error('OAuth2 flow failed. No authorization code received.');
  }

  // Exchange the code for an access token
  const { tokens } = await OAUTH2_CLIENT.getToken(code);


  // Save the token for later use (you can store it securely)
  // Define the correct path to store the token
  const tokenPath = path.join(__dirname, 'token.json');
  
  // Save the token for later use (you can store it securely)
  fs.writeFileSync(tokenPath, JSON.stringify(tokens), 'utf8');
  //fs.writeFileSync('token.json', JSON.stringify(tokens, null, 2));

  return tokens.access_token;
}

// Function to load the saved token from a file
function loadAccessToken() {
  // Define the correct path to the token file
  const tokenPath = path.join(__dirname, 'token.json');
  // console.log('Token path:', tokenPath);

  if (fs.existsSync(tokenPath)) {
    const tokens = JSON.parse(fs.readFileSync(tokenPath, 'utf-8'));
    return tokens.access_token;
  }

  return null;
}

// Function to authorize using the saved or new OAuth token
async function authorize(cron) {
  //cron = cron || null;
  console.log(cron);
  let accessToken = loadAccessToken();

  if (!accessToken) {
    console.log('No saved access token found, starting OAuth2 flow...');
    accessToken = await getOAuthTokenWithPuppeteer();
  
  }else if(cron){

    console.log('Cron refreshing the token');
    accessToken = await getOAuthTokenWithPuppeteer();

  } else {
    console.log('Using saved access token...');
  }

  OAUTH2_CLIENT.setCredentials({ access_token: accessToken });
  return OAUTH2_CLIENT;
}

module.exports = { authorize };
