const { argv } = require('node:process');
const { vitalink } = require("../src/integrations/laboredge");

// quick and dirty

(async ()=>{

	await vitalink.init();
	return

})();
