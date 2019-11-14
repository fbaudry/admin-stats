const fs = require('fs');
const puppeteer = require('puppeteer');

const start = async(league) => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(league.link + 'classement');
    await page.waitFor(3000);

    const resultats = await page.evaluate(() => {
        let elements = Array.from(document.querySelectorAll('.table__row'));

        return elements.map(element => {
            return {
                team_name: element.children[1].children[0].children[1].innerText,
                nb_played_game: parseInt(element.children[2].innerText),
                nb_win_game: parseInt(element.children[3].innerText),
                nb_nul_game: parseInt(element.children[4].innerText),
                nb_lost_game: parseInt(element.children[5].innerText),
                nb_goals: element.children[6].innerText,
                nb_points: parseInt(element.children[7].innerText),
                results: [
                    element.children[8].children[1].innerText,
                    element.children[8].children[2].innerText,
                    element.children[8].children[3].innerText,
                    element.children[8].children[4].innerText,
                    element.children[8].children[5].innerText,
                ]
            };
        });
    });

    browser.close();
    let classement = resultats.filter(league => { return null != league; });

    let dir = 'public/json/leagues/' + league.name;
    if (!fs.existsSync(dir)){
        fs.mkdirSync(dir);
    }
    fs.writeFileSync(
        dir + '/classement.json',
        JSON.stringify(classement, null, 2)
    );

    return classement;
};

module.exports.start = start;

