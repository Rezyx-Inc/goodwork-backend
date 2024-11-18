const REDIRECT_URI = 'https://google.com';
const puppeteer = require('puppeteer');

async function getOAuthTokenWithPuppeteer() {
  
  
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();
  
    // Navigate to the authorization URL
    await page.goto(REDIRECT_URI);
  
    
    // Close the browser
    await browser.close();
  
  }
  getOAuthTokenWithPuppeteer();