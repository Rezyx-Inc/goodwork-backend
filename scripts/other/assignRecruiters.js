const { argv } = require('node:process');
const { updateRecruiterId } = require('../src/mysql/queries.js');

// quick and dirty

if( argv[2] == "update" ){
	(async ()=>{

		var orgId = argv[3].trim();
		var update = await updateRecruiterId(orgId);
		console.log(update);
		return

	})();
}
