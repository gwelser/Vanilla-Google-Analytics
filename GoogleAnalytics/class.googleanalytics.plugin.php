<?php
if (!defined('APPLICATION')) die();

$PluginInfo['GoogleAnalytics'] = array(
	'Name' => 'Google Analytics',
	'Description' => 'Adds Google <a href="https://support.google.com/analytics/answer/2790010?hl=en">Universal Analytics</a> to all pages.',
	'Version' => '1.0',
	'Author' => 'Glenn Welser',
	'AuthorEmail' => 'glenn.welser@gmail.com',
	'AuthorUrl' => 'http://vanillaforums.org/profile/mightyrocket',
	'SettingsUrl' => '/plugin/googleanalytics',
	'SettingsPermission' => 'Garden.AdminUser.Only',
	'MobileFriendly' => TRUE
);

class GoogleAnalyticsPlugin extends Gdn_Plugin {

	private $universal = "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', '{{trackingid}}', 'auto');
  ga('send', 'pageview');
</script>";

	public function __construct() {}

	public function PluginController_GoogleAnalytics_Create($Sender) {

		$Sender->Title('Google Analytics');
		$Sender->AddSideMenu('plugin/googleanalytics');

		$Sender->Form = new Gdn_Form();

		$this->Dispatch($Sender, $Sender->RequestArgs);

	}

	public function Controller_Index($Sender) {

		$Sender->Permission('Vanilla.Settings.Manage');

		$Sender->SetData('PluginDescription',  $this->GetPluginKey('Description'));

		$Validation = new Gdn_Validation();
		$ConfigurationModel = new Gdn_ConfigurationModel($Validation);
		$ConfigurationModel->SetField(array(
			'Plugins.GoogleAnalytics.TrackingId' => '',
            'Plugins.GoogleAnalytics.IncludeDashboard' => false
		));

		$Sender->Form->SetModel($ConfigurationModel);

		if ($Sender->Form->AuthenticatedPostBack() === false) {

			$Sender->Form->SetData($ConfigurationModel->Data);

		} else {

			$Saved = $Sender->Form->Save();
			if ($Saved) {
				$Sender->StatusMessage = T('Your changes have been saved.');
			}

		}

		$Sender->Render($this->GetView('googleanalytics.php'));

	}

	public function Base_GetAppSettingsMenuItems_Handler($Sender) {

		$Menu = &$Sender->EventArguments['SideMenu'];
		$Menu->AddLink('Add-ons', 'Google Analytics', 'plugin/googleanalytics', 'Garden.AdminUser.Only');

	}

	public function Base_Render_Before($Sender) {

		$IncludeDashboard = C('Plugins.GoogleAnalytics.IncludeDashboard', false);
		if ($Sender->Application === 'Dashboard' && !$IncludeDashboard) return;

		$TrackingId = C('Plugins.GoogleAnalytics.TrackingId', false);
		if (!$TrackingId) return;

		$analytics = str_replace('{{trackingid}}', $TrackingId, $this->universal);

		$Sender->Head->AddString($analytics);

	}

	public function Setup() {}

	public function OnDisable() {}

}
