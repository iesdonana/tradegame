<?php

use yii\helpers\Url;

class HomeCest
{
    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->see('TradeGame');

        $I->seeLink('Comienza');
        $I->click('Comienza');
        $I->wait(2); // wait for page to be opened

        $I->see('Iniciar sesión.');
    }
}
