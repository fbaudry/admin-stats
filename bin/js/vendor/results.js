const fs = require('fs');
const puppeteer = require('puppeteer');

const start = async(league) => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(league.link + 'resultats');
    await page.waitFor(3000);
    const games = await page.evaluate(() => {
        let tab = Array.from(document.querySelectorAll('.sportName > div'));
        let children = tab.map(child => {
            if(child.classList.contains('event__header')) {
                return null;
            }

            if(child.classList.contains('event__round')) {
                return child.innerHTML;
            }

            if(child.classList.contains('event__match') && child.children[0].classList.contains('event__check')) {
                return {
                    home_team_name: child.children[2].innerText,
                    home_team_score: parseInt(child.children[3].children[0].innerText),
                    away_team_name: child.children[4].innerText,
                    away_team_score: parseInt(child.children[3].children[1].innerText)
                };
            }

            return {
                home_team_name: child.children[1].innerText,
                home_team_score: parseInt(child.children[2].children[0].innerText),
                away_team_name: child.children[3].innerText,
                away_team_score: parseInt(child.children[2].children[1].innerText)
            };
        });
        return children.filter(child => null !== child).filter(child => {return 'object' == typeof (child);});
    });

    browser.close();

    let dir = 'public/json/leagues/' + league.name;
    fs.writeFileSync(
        dir + '/results.json',
        JSON.stringify(games, null, 2)
    );

    return games;
};

module.exports.start = start;


