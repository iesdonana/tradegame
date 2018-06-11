<?php
use yii\helpers\Url;

class InicioCest
{
    public function checkOpen(\FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->see('TradeGame');
        $I->seeLink('Comienza');
        $I->click('Comienza');
        $I->see('Iniciar sesión');
    }
}
