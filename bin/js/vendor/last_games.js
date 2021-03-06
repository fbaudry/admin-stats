const fs = require('fs');
const puppeteer = require('puppeteer');

const start = async(league) => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(league.link + 'resultats');
    await page.waitFor(3000);

    const resultats = await page.evaluate(() => {
        let rows = Array.from(document.querySelectorAll('.sportName > div'));

        let round = 0;
        let games = [];
        for(let index = 0; index < rows.length; ++index) {
            let row = rows[index];

            if(row.classList.contains('event__header')) {
                continue;
            }

            if(row.classList.contains('event__round')) {
                ++round;
            }
            if(round > 1) { break; }

            if(!row.classList.contains('event__match')) {continue;}

            if(row.classList.contains('event__match') && row.children[0].classList.contains('event__check')) {
                games.push({
                    home_team_name: row.children[2].innerText,
                    away_team_name: row.children[4].innerText,
                    home_team_score: parseInt(row.children[3].children[0].innerText),
                    away_team_score: parseInt(row.children[3].children[1].innerText)
                });
                continue;
            }

            games.push({
                home_team_name: row.children[1].innerText,
                away_team_name: row.children[3].innerText,
                home_team_score: parseInt(row.children[2].children[0].innerText),
                away_team_score: parseInt(row.children[2].children[1].innerText)
            });
        }

        return games;
    });

    browser.close();
    let last_games = resultats.filter(league => { return null != league; });

    let dir = 'public/json/leagues/' + league.name;
    if (!fs.existsSync(dir)){
        fs.mkdirSync(dir);
    }
    fs.writeFileSync(
        dir + '/last_games.json',
        JSON.stringify(last_games, null, 2),
    );

    return last_games;
};


module.exports.start = start;



