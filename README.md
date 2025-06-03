![lackinggeneral](https://github.com/user-attachments/assets/a9cd61e7-c510-4d16-9fee-c98805557142)
Om repo:

Detta github repo inehåller våran report sida till kursen mvc på bth. 
Denna sidan kommer att gradvis uppdateras med olika sidor som använder sig av objektorienterad kod för att åstadkomma olika interaktioner med bland annat databas.

Krav:
Du måste ha php 8.3 eller senare. Du behöver även ha installerat composer och php package manager. Sedan kan du klona repot.

Hur du kommer igång:
För att klona report så trycker du först på den gröna "code" ovanför filerna i repot.
Sedan väljer du vilket format du vill kopiera det, https eller SSH. Kopiera url och sedan kan du öppna din terminal och ta dig till mappen du vill ha repot kopierat till. Kör "git clone (SSH eller https)" i terminalen.
Efter detta så behöver du installera några saker för att kunna köra servern.
Kör dessa kommandon i terminalen under samma folder som repot står i:
composer require webapp
composer require twig
composer require symfony/webpack-encore-bundle
npm install
npm run build (kör detta efter varje ändring i CSS)
curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
sudo apt install symfony-cli
sudo apt install libnss3-tools
symfony server:ca:install

Hur gör du nu?:
Du kan nu använda symfony server:start för att starta servern.
Om du är en bth student eller annars har tillgång till dbwebb och vill kunna göra dbwebb publish så måste du även redigera filväg i .httaccess så den är korrekt samt använder din acronym.

Badges:
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/git-Stella/mvc-report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/git-Stella/mvc-report/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/git-Stella/mvc-report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/git-Stella/mvc-report/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/git-Stella/mvc-report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/git-Stella/mvc-report/build-status/main)
