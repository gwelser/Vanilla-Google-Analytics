<?php if (!defined('APPLICATION')) exit(); ?>

<h1><?php echo T($this->Data['Title']); ?></h1>
<div class="Info">
   <?php echo T($this->Data['PluginDescription']); ?>
</div>
<h3><?php echo T('Settings'); ?></h3>
<?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
?>
<ul>
   <li><?php
      echo $this->Form->Label('Tracking ID', 'Plugins.GoogleAnalytics.TrackingId');
      echo $this->Form->Textbox('Plugins.GoogleAnalytics.TrackingId');
   ?></li>
   <li><?php
	   echo $this->Form->Label('Include', 'Plugins.GoogleAnalytics.IncludeDashboard');
	   echo $this->Form->Checkbox('Plugins.GoogleAnalytics.IncludeDashboard', T('Include Universal Analytics script on Dashboard pages?'));
   ?></li>
</ul>
<?php
   echo $this->Form->Close('Save');
?>