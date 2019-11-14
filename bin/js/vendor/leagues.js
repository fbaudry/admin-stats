const fs = require('fs');
const puppeteer = require('puppeteer');

const flash_resultats_homepage = 'https://www.flashresultats.fr';

const start = async() => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(flash_resultats_homepage);
    await page.waitFor(3000);

    const resultats = await page.evaluate(() => {
        let leagues = Array.from(document.querySelectorAll('#my-leagues-list li'));

        return leagues.map(league => {
            if('' === league.title) {
                return null;
            }

            if(league.title.includes('EUROPE')) {
                return null;
            }

            return {
                link: league.children[1].href,
                name: league.children[1].innerText
            };
        });
    });

    browser.close();
    let leagues = resultats.filter(league => { return null != league; });

    fs.writeFileSync(
        'public/json/leagues/leagues.json',
        JSON.stringify(leagues, null, 2)
    );

    return leagues;
};


module.exports.start = start;
