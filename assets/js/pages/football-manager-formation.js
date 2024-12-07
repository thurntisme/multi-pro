const GK = {posName: "GK", x: (width * 4) / pitchX, y: height / 2};
const LB = {posName: "LB", x: (width * 18) / pitchX, y: height / 6};
const LWB = {...LB, x: LB.x + 10};
const RB = {posName: "RB", x: (width * 18) / pitchX, y: (height * 5) / 6};
const RWB = {...RB, x: RB.x + 10};
const LCB = {posName: "CB", x: (width * 12) / pitchX, y: height / 3};
const RCB = {posName: "CB", x: (width * 12) / pitchX, y: (height * 2) / 3};
const CDM = {posName: "CDM", x: (width * 24) / pitchX, y: height / 2};
const CDMd = {...CDM, x: CDM.x - 15};
const LCDM = {
    posName: "CDM",
    x: (width * 24) / pitchX,
    y: height / 3,
};
const LCDMd = {...LCDM, x: LCDM.x - 15};
const RCDM = {
    posName: "CDM",
    x: (width * 24) / pitchX,
    y: (height * 2) / 3,
};
const RCDMd = {...RCDM, x: RCDM.x - 15};
const LCM = {posName: "CM", x: (width * 30) / pitchX, y: height / 3};
const LCMd = {...LCM, x: LCM.x - 15};
const RCM = {posName: "CM", x: (width * 30) / pitchX, y: (height * 2) / 3};
const RCMd = {...RCM, x: RCM.x - 15};
const LM = {posName: "LM", x: (width * 30) / pitchX, y: height / 6};
const LMa = {...LM, x: LM.x + 15};
const RM = {posName: "RM", x: (width * 30) / pitchX, y: (height * 5) / 6};
const RMa = {...RM, x: RM.x + 15};
const LF = {posName: "LF", x: (width * 45) / pitchX, y: (height * 3) / 8};
const RF = {posName: "RF", x: (width * 45) / pitchX, y: (height * 5) / 8};
const LW = {posName: "LW", x: (width * 40) / pitchX, y: height / 6};
const LWd = {...LW, x: LW.x - 15};
const RW = {posName: "RW", x: (width * 40) / pitchX, y: (height * 5) / 6};
const RWd = {...RW, x: RW.x - 15};
const CAM = {posName: "CAM", x: (width * 35) / pitchX, y: height / 2};
const CF = {posName: "CF", x: (width * 40) / pitchX, y: height / 2};
const ST = {posName: "ST", x: (width * 45) / pitchX, y: height / 2};
const LCB_3 = {
    posName: "CB",
    x: (width * 12) / pitchX,
    y: (height * 1) / 3 - 12,
};
const CB_3 = {posName: "CB", x: (width * 12) / pitchX, y: height / 2};
const RCB_3 = {
    posName: "CB",
    x: (width * 12) / pitchX,
    y: height - (height * 1) / 3 + 12,
};

// Function to generate player positions for various formations
function generateFormation(formation) {
    switch (formation) {
        case "433":
            return [GK, LB, LCB, RCB, RB, CDM, LCM, RCM, LW, ST, RW];
        case "442":
            return [GK, LB, LCB, RCB, RB, LM, LCMd, RCMd, RM, LF, RF];
        case "352":
            return [GK, LCB_3, CB_3, RCB_3, LCDMd, RCDMd, LM, RM, CAM, LF, RF];
        case "4231":
            return [GK, LB, LCB, RCB, RB, LCDMd, RCDMd, CAM, LM, RM, ST];
        case "4141":
            return [GK, LB, LCB, RCB, RB, CDMd, LCM, RCM, LMa, RMa, ST];
        case "541":
            return [GK, LWB, LCB_3, CB_3, RCB_3, RWB, LCDM, RCDM, LMa, RMa, ST];
        case "4312":
            return [GK, LB, LCB, RCB, RB, CDM, LCM, RCM, CF, LF, RF];
        case "343":
            return [GK, LCB_3, CB_3, RCB_3, LM, LCDM, RCDM, RM, LW, ST, RW];
        case "4222":
            return [GK, LB, LCB, RCB, RB, LWd, LCMd, RCMd, RWd, LF, RF];
        default:
            console.error("Invalid formation");
            return [];
    }
}
