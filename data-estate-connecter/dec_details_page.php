<?php require_once 'admin_functions.php';?>
<div class="wrap">
	<div class="headline_api_detail">
	<?php echo "<h2>" . __('Data Estate Connecter Settings') . "</h2>"; ?>
	</div>
	<p>Please show the default values when nothing’s entered. Leave blank if “default” is blank in the table below. </p>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Name</th>
<!-- 				<th>Options Key</th> -->
				<th>Type</th>
				<th>Description</th>
				<th>Value/Validation</th>
				<th>Default</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th scope="row">API URL</th>
<!-- 				<td>dec-api-url</td> -->
				<td>text</td>
				<td>Data Estate API’s URL. The URL usually doesn’t need changing and can stay as the default value. Only change this when you’re switching to a separate environment (such as UAT, development or your own custom instance).</td>
				<td>Valid uri includingprotocol.REQUIRED</td>
				<td>http://api.dataestate.net/v1/</td>
			</tr>
			<tr>
				<th scope="row">API Key</th>
<!-- 				<td>dec-api-key</td> -->
				<td>text</td>
				<td>A valid Data Estate API Key. If you wish to access ATDW’s Tourism Content,you will need to register for a Data Estate account with a valid ATDW API Key.</td>
				<td>REQUIRED</td>
				<td></td>
			</tr>
			<tr>
				<th scope="row">End Point</th>
<!-- 				<td>dec-api-endpoint</td> -->
				<td>text</td>
				<td>The main <strong>Estate</strong> endpoint to use. Usually this is going to be estates/data/ so it's best to just leave it untouched. </td>
				<td>REQUIRED</td>
				<td>estates/data/</td>
			</tr>
			<tr>
				<th scope="row">Google Maps API key</th>
<!-- 				<td>dec-api-endpoint</td> -->
				<td>text</td>
				<td>A valid Google Map API key is needed, if you wish to use the Map widget.</td>
				<td></td>
				<td></td>
			</tr>
			<!-- <tr>
				<th scope="row">Shortcode Prefix</th>
				<td>dec-api-key</td>
				<td>text</td>
				<td>A valid Data Estate API Key. If you wish to access ATDW’s Tourism Content,you will need to register for a Data Estate account with a valid ATDW API Key.</td>
				<td>REQUIRED</td>
				<td></td>
			</tr> -->
		</tbody>
	</table>
	<div class="headline_api_detail">
		<?php echo "<h2>" . __('Configration For Data Estate Connecter') . "</h2>"; ?>
	</div>
	<div class="api_detail_form">
		<?php if($msz){
		echo $msz;
		}?>
		<form action="" method="post" name="form">
			<div class="row">
				<div class="col-md-6 col-sm-12 col-xs-12">
					<label for="api_base_url">Data Estate API URL</label>
					<div class="form-group">
						<input type="text" placeholder="Enter Data Estate API URL" name="api_base_url" id="api_base_url"  class="form-control" autocomplete="off" required value="<?php echo $api_base_url_1;?>">
					</div>
				</div>
				<div class="col-md-6 col-sm-12 col-xs-12">
					<label for="api_end_point">Data Estate End Point</label>
					<div class="form-group">
						<input type="text" placeholder="Enter Data Estate API URL" name="api_end_point" id="api_end_point"   class="form-control" autocomplete="off" required value="<?php echo $api_end_point_1;?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<label for="api_key">Your Api Key</label>
					<div class="form-group">
						<input type="text" placeholder="Enter Your Api Key" name="api_key" id="api_key"  class="form-control" required autocomplete="off" value="<?php echo $api_key_1;?>">
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<label for="gmap_key">Google Maps API Key</label>
					<div class="form-group">
						<input type="text" placeholder="Enter Your Google Map Api Key" name="gmap_key" id="gmap_key"  class="form-control" autocomplete="off" value="<?php echo $gmap_key_1;?>">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<label for="api_key">Main Estate ID</label>
					<div class="form-group">
						<input type="text" placeholder="Enter Your Estate ID" name="estate_id" id="estate_id"  class="form-control" required autocomplete="off" value="<?php echo $estate_id_1;?>">
					</div>
				</div>

			</div>

			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<input type="submit" name="api_detail_submit" class="api_detail_btn" value="Save all changes"/>
					</div>
				</div>
			</div>
			<!-- <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12"><h3>Category Mapping</h3></div>
				<div class="col-md-6 col-sm-12 col-xs-12 form-inline">
					<div class="form-group">
						<label for="cat_map_accomm">Accommodation</label>
						<input type="text" placeholder="Alias (i.e. Where to stay?)" name="cat_map_accomm" id="cat_map_accomm" class="form-control" autocomplete="off">
					</div>
				</div>

			</div> -->
		</form>
	</div>
</div>
