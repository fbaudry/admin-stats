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

        let days = {};
        let index= null;
        children = children.filter(el => {
            return el != null;
        });

        for(let i = 0; i < children.length; i++) {
            if(typeof children[i] === 'string' && children[i].includes('Journée')) {
                index = children[i];
                days[index] = [];
                continue;
            }
            days[index].push(children[i]);
        }

        return days;
    });

    days = Object.keys(games);
    let teams = {};
    let nul_gamers = [];

    for(let i = 0; i < days.length; ++i) {
        let day_games = games[days[i]];

        for(let j = 0; j < day_games.length; ++j) {
            let game = day_games[j];

            if(!(game.home_team_name in teams)) {
                teams[game.home_team_name] = 0;
            }

            if(!(game.away_team_name in teams)) {
                teams[game.away_team_name] = 0;
            }

            if(game.home_team_score !== game.away_team_score) {
                if(!nul_gamers.includes(game.home_team_name)) {
                    ++teams[game.home_team_name];
                }

                if(!nul_gamers.includes(game.away_team_name)) {
                    ++teams[game.away_team_name];
                }

                continue;
            }

            nul_gamers.push(game.home_team_name);
            nul_gamers.push(game.away_team_name);
        }
    }

    browser.close();

    let results = [];
    for (let i = 0; i < Object.keys(teams).length; ++i) {
        let key = Object.keys(teams)[i];
        results.push({
            team_name: key,
            nb_points: teams[key]
        });
    }

    results.sort((a, b) => {
        if ( a.value < b.value ){
            return 1;
        }
        if ( a.value > b.value ){
            return -1;
        }
        return 0;
    });

    let dir = 'public/json/leagues/' + league.name;
    if (!fs.existsSync(dir)){
        fs.mkdirSync(dir);
    }

    fs.writeFileSync(
        dir + '/game_without_nul.json',
        JSON.stringify(results, null, 2)
    );

    return results;
};

module.exports.start = start;


