const fs = require('fs');
const puppeteer = require('puppeteer');

const start = async(league) => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(league.link + 'archives');
    await page.waitFor(3000);

    const seasons = await page.evaluate(() => {
        let elements = Array.from(document.querySelectorAll('#tournament-page-archiv > .profileTable__row--background'));

        return elements.map(element => {
            if(10 > element.children[1].innerHTML.length) {
                return null;
            }

            return {
                name: element.children[0].innerText,
                link: element.children[0].children[0].children[0].href
            }
        }).filter(el => {return el != null;}).slice(0, 10);
    });

    for(let index = 0; index < seasons.length; ++index) {
        let season = seasons[index];
        await page.goto(season.link + 'resultats');
        await page.waitFor(3000);

        while(await page.evaluate(() => {return !!document.querySelector('.event__more'); })) {
            await page.click('.event__more');
            await page.waitFor(3000);
        }

        const archives = await page.evaluate(() => {
            let rows = Array.from(document.querySelectorAll('.sportName > div'));

            let games = {};
            let day = null;

            for(let i = 0; i < rows.length; ++i) {
                let row = rows[i];
                if(row.classList.contains('event__header')) {
                    continue;
                }

                if(row.classList.contains('event__round')) {
                    day = row.innerText;
                    games[day] = [];
                    continue;
                }

                if(row.children[0].innerHTML.includes('event__stage--block')) {
                    continue;
                }

                games[day].push({
                    home_team_name: row.children[1].innerText,
                    home_team_score: parseInt(row.children[2].children[0].innerText),
                    away_team_name: row.children[3].innerText,
                    away_team_score: parseInt(row.children[2].children[1].innerText)
                });
            }

            return games;
        });


        let dir = 'public/json/leagues/' + league.name + '/archives/' + season.name.split(' ').pop().replace('/', '-');
        if (!fs.existsSync(dir)){
            fs.mkdirSync(dir, {recursive: true});
        }
        fs.writeFileSync(
            dir + '/games.json',
            JSON.stringify(archives, null, 2)
        );
    }

    browser.close();

    return;
};


module.exports.start = start;

