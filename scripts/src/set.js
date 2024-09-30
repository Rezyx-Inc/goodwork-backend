const TelegramBot = require("node-telegram-bot-api");

// replace the value below with the Telegram token you receive from @BotFather
const token = "6799015820:AAEs6qtropo19DzvTr-A_fvYGGVKenSgP3E";

// Create a bot that uses 'polling' to fetch new updates
const bot = new TelegramBot(token, { polling: false });
const groupId = -4197331073;

module.exports.report = async (msg) => {
  console.log("MESSAGING", msg);
  await bot.sendMessage(groupId, msg);
};
