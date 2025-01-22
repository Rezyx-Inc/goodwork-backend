const {Organizations} = require('../models/Orgs');

module.exports.getNextUpRecruiter = async function (orgId){

    try {

        var org = await Organizations.findOne({ orgId: orgId});

        if (!org) {
            return orgId;
        }

        if (org.recruiters.length == 0) {
            return orgId;
        }

        var upNextRecruiter;

        if(org.recruiters.length == 1){

            upNextRecruiter = org.recruiters[0];
            upNextRecruiter.worksAssigned = upNextRecruiter.worksAssigned + 1;

        }else{

            for (let i = 0; i < org.recruiters.length; i++){

                var len = org.recruiters.length;
                var next = org.recruiters[(i+1)%len];

                if(org.recruiters[i].upNext === true){
                    upNextRecruiter = org.recruiters[i];
                    next.upNext = true;
                    break;
                }
            }

            upNextRecruiter.worksAssigned++;
            upNextRecruiter.upNext = false;
        }

        await org.save();

        // i want to return it with the recruiter id
        return upNextRecruiter.id

    } catch (e) {

        console.log("Unable to save organization.", e);
        return false
    }
}
