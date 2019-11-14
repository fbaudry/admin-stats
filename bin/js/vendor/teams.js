const fs = require('fs');
const puppeteer = require('puppeteer');

const start = async(league) => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(league.link + 'equipes');
    await page.waitFor(3000);

    const resultats = await page.evaluate(() => {
        let leagues = Array.from(document.querySelectorAll('#tournament-page-participants .leagueTable__team'));

        return leagues.map(league => {
            return league.innerText;
        });
    });

    browser.close();

    let teams = resultats.filter(league => { return null != league; });

    let dir = 'public/json/leagues/' + league.name;
    if (!fs.existsSync(dir)){
        fs.mkdirSync(dir);
    }
    fs.writeFileSync(
        dir + '/teams.json',
        JSON.stringify(teams, null, 2),
    )
};

module.exports.start = start;


