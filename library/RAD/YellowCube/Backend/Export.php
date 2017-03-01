<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Backend;

use Contao\BackendTemplate;
use Contao\Environment;
use Contao\RequestToken;
use executable;
use Contao\Backend;
use Haste\Input\Input;


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
        return 'hallo';

        $template = new BackendTemplate('be_yellowcube_masterdata');
        $template->action = ampersand(Environment::get('request'));
        $template->jobHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['yellowcube_masterdata'];
        $template->isActive = $this->isActive();

        if ('yellowcube_masterdata' == Input::get('act')) {
            if (!isset($_GET['rt']) || !RequestToken::validate(Input::get('rt'))) {
                $this->Session->set('INVALID_TOKEN_URL', Environment::get('request'));
                $this->redirect('contao/confirm.php');
            }

            $template->jobNote = $GLOBALS['TL_LANG']['tl_maintenance']['yellowcube_masterdata_note'];
            $template->jobLoading = $GLOBALS['TL_LANG']['tl_maintenance']['yellowcube_masterdata_loading'];
            $template->jobComplete = $GLOBALS['TL_LANG']['tl_maintenance']['yellowcube_masterdata_note_complete'];
            $template->jobContinue = $GLOBALS['TL_LANG']['MSC']['yellowcube_masterdata_note_continue'];
            $template->isRunning = true;
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
