<?php
class PublicacionesCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['videojuegos-usuarios/all-publicaciones']);
    }

    public function checkOpen(\FunctionalTester $I)
    {
        $I->see('Publicar nuevo videojuego');
    }

    public function checkPublicarUnlogged(\FunctionalTester $I)
    {
        $I->click('Publicar nuevo videojuego');
        $I->see('Iniciar sesión');
    }
}
