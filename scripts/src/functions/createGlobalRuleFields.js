const { GlobalRuleFields } = require('../models/Orgs');
const ruleFields = require('../data/ruleFields');

const createGlobalRuleFields = async () => {

    try {

        const docs = await GlobalRuleFields.find({});
            if (docs.length === 0) {

                await GlobalRuleFields.create({ ruleFields });
                console.log("Global rule fields created successfully");

            } else {

                console.log("Global rule fields already exist");

            }

    } catch (err) {

        console.error("Error while checking or creating global rule fields", err);
        report('error', 'createGlobalRuleFields.js', err);

    }
};

module.exports = createGlobalRuleFields;
