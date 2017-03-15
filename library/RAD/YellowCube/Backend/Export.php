<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Backend;

use Contao\BackendTemplate;
use Contao\Environment;
use Contao\Model\Collection;
use Contao\RequestToken;
use executable;
use Contao\Backend;
use Haste\Input\Input;
use RAD\Event\EventDispatcher;
use RAD\YellowCube\Model\Product\YellowCube;


/**
 * Class Export
 */
class Export extends Backend implements executable
{
    /**
     * @return string
     */
    public function run()
    {
        $template = new BackendTemplate('be_yellowcube_masterdata');
        $template->action = ampersand(Environment::get('request'));
        $template->jobHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['yellowcube_masterdata'][0];
        $template->jobDescription = $GLOBALS['TL_LANG']['tl_maintenance']['yellowcube_masterdata'][1];
        $template->isActive = $this->isActive();

        if ('yellowcube_masterdata' == Input::get('act')) {
            if (!isset($_GET['rt']) || !RequestToken::validate(Input::get('rt'))) {
                $this->Session->set('INVALID_TOKEN_URL', Environment::get('request'));
                $this->redirect('contao/confirm.php');
            }

            $collection = YellowCube::findByType('yellowcube', true);

            if ($collection instanceof Collection) {
                $i = $collection->count();

                foreach ($collection as $item) {
                    if ($item instanceof YellowCube) {
                        EventDispatcher::getInstance()->dispatch('yellowcube.sendProduct', $item);
                    }
                }
            }
            else {
                $i = 0;
            }

            $template->jobMessage = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['yellowcube_masterdata_message'], $i);
            $template->theme = Backend::getTheme();

            return $template->parse();
        }

        $template->jobSubmit = $GLOBALS['TL_LANG']['tl_maintenance']['yellowcube_masterdata_submit'][0];

        return $template->parse();
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return 'yellowcube_masterdata' == Input::get('act');
    }
}
