<?php
// vim: set tabstop=4 softtabstop=0 noexpandtab shiftwidth=4:
/**
* remoteCP 4 Plugin Restart
* ütf-8 release
*
* @package remotecp-plugin-restart
* @author Jan Erik Petersen
* @copyright (c) 2015
* @license GNU GPL v3.0
* @version 1.0 (for 4.0.3.5)
*/
class Restart extends rcp_plugin
{
	public	$display		= 'side';
	public	$title			= 'Restart';
	public	$author			= 'Jan Erik Petersen';
	public	$version		= '1.0';
	public	$nservcon		= false;
	public	$vpermissions	= array('offlinelogin');
	public	$apermissions	= array(
		'fireAction'		=> 'maintenance'
	);

	// Configuration
	private	$script			= null;
	private	$services		= array();

	// Request
	private	$service		= null;
	private	$op				= null;

	public function onLoadSettings($settings)
	{
		$this->script	= (string) $settings->script;
		foreach ($settings->services->service as $service)
		{
			$this->services[(string) $service->param]	= (string) $service->name;
		}
	}

	public function onLoad()
	{
		$this->service	= array_key_exists('service', $_REQUEST)	? $_REQUEST['service']	: null;
		$this->op		= array_key_exists('op', $_REQUEST)			? $_REQUEST['op']		: null;
	}

	public function onOutput()
	{
		if (!function_exists('exec'))
		{
			Core::getObject('messages')->add(pt_errnoexec, true);
			return;
		}

		if (count($this->services) === 0)
		{
			Core::getObject('messages')->add(pt_errnoservices, true);
			return;
		}

		foreach ($this->services as $param => $name)
		{
			$status = 255;
			$ignored;
			exec($this->script . " " . $param . " status", $ignored, $status);

			echo "<form action='ajax.php' method='post' id='restart' name='restart' class='postcmd' rel='{$this->display}area'>";
			echo "	<input type='hidden' name='plugin' value='{$this->id}' />";
			echo "	<input type='hidden' name='service' value='{$param}' />";
			echo "	<input type='hidden' name='action' value='fireAction' />";
			echo "<fieldset>";
			echo "<div class='legend'>{$name}</div>";
			echo "  <div class='f-row'>
						<label>".pt_status."</label>
						<div class='f-field'>";
			switch ($status)
			{
				case 0: echo pt_statusrunning; break;
				case 1: echo pt_statuscrashed; break;
				case 2: echo pt_statuscrashed; break;
				case 3: echo pt_statusstopped; break;
				default: echo pt_statusunknown; break;
			}
			echo "	</div>
				  </div>";

			if (Core::getObject('session')->checkPerm($this->apermissions['fireAction']))
			{
				echo "	<div class='f-row'>
							<label for='op'>".pt_action."</label>
							<div class='f-field'>
								<select name='op'>";
				if ($status	!== 0)
				{
					echo "			<option value='start'>".pt_actionstart."</option>";
				}
				else
				{
					echo "			<option value='restart'>".pt_actionrestart."</option>";
					echo "			<option value='stop'>".pt_actionstop."</option>";
				}
				echo "			</select>
							</div>
							<button type='submit' title='".ct_submit."' class='wide'>".ct_submit."</button>
						</div>";
			}

			echo "</fieldset>
				  </form>";
		}
	}

	public function fireAction()
	{
		if (!array_key_exists($this->service, $this->services))
		{
			Core::getObject('messages')->add(pt_errunknownservice, true);
			return;
		}
		if (!in_array($this->op, array('start', 'stop', 'restart')))
		{
			Core::getObject('messages')->add(pt_errinvalidop, true);
			return;
		}

		$returnval = 255;
		$output = array();
		exec($this->script . " " . $this->service . " " . $this->op . " 2>&1", $output, $returnval);

		if ($returnval === 0)
		{
			Core::getObject('messages')->add(pt_actionsuccess);
		}
		else
		{
			$message = " [" . $returnval . "]";
			if (count($output) !== 0)
			{
				$message .= ": " . str_replace("\n", "<br>", htmlspecialchars(implode("\n", $output)));
			}
			Core::getObject('messages')->add(pt_actionfailure . $message, true);
		}
	}
}
?>
