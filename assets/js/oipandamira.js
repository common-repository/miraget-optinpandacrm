function miragetPage(){
//some comments
	const apSt = apiStatusHtml(apiStatus) ;

	var date = new Date( lastUpdate * 1000  );

	const lastUp = date.toString().replace('GMT+0100 (Romance Standard Time)','') ;
    if( update ) document.location = location.href ;
	html +=  `<div id="wpbody" role="main">

	<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">

					<div class="miraget-apiStatus ${apiStatusHtml(apiStatus)[1]} miragetApiCalls">
					 ${apiStatusHtml(apiStatus)[0]}
					</div>

	<div class="wrap">
		<h1>Miraget Optin Panda CRM Admin panel</h1>

		<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
		${rightSide()}
		<div id="postbox-container-2" class="postbox-container">
		<div id="normal-sortables" class="meta-box-sortables ui-sortable">

	<div id="dashboard_activity" class="postbox ">
	<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Activity</span><span class="toggle-indicator" aria-hidden="true"></span></button><h2 class="hndle ui-sortable-handle"><span>Activity</span></h2>
	<div class="inside">
	<div id="activity-widget">
	<div id="published-posts" class="activity-block">
	 <h3>Recently Update</h3>
		<ul>
		<li><span>${lastUp}</span>

		</li>
		</ul>
	</div>
	<div id="latest-comments" class="activity-block">
    ${emailActivity()}

    <p class="community-events-footer">
		<a href="https://miraget.com/wordpress-plugin-miraget-optin-panda-sync-to-crm/" target="_blank" >
		    Miraget work with Panda
			<span class="screen-reader-text">(opens in a new window)</span>
		</a>
	</p>

	<div class="hidden" id="trash-undo-holder">
		<div class="trash-undo-inside">Comment by <strong></strong> moved to the trash. <span class="undo untrash"><a href="#">Undo</a></span></div>
	</div>
	<div class="hidden" id="spam-undo-holder">
		<div class="spam-undo-inside">Comment by <strong></strong> marked as spam. <span class="undo unspam"><a href="#">Undo</a></span></div>
	</div>
	</div></div></div>
	</div>
	</div>	</div>
        <!-- left side -->
		<div id="postbox-container-3" class="postbox-container">
		<div id="column3-sortables" class="meta-box-sortables ui-sortable empty-container" data-emptystring="Drag boxes here"></div>	</div>
		<div id="postbox-container-4" class="postbox-container">
		<div id="column4-sortables" class="meta-box-sortables ui-sortable empty-container" data-emptystring="Drag boxes here"></div>	</div>
	</div>

	<input type="hidden" id="closedpostboxesnonce" name="closedpostboxesnonce" value="0a3f8185ad"><input type="hidden" id="meta-box-order-nonce" name="meta-box-order-nonce" value="fa91e513d6">	</div><!-- dashboard-widgets-wrap -->

	</div><!-- wrap -->

	<div class="clear"></div></div><!-- wpbody-content -->
	<div class="clear"></div></div>` ;
}
function errorPost(){

	if( errors_post === 1 &&  is_post )   {

		return `
		<div class="miragerErrorPost">
		  <p>Error ! </p>
		  <p>Please fill the empty values</p>
		  <p></p>
		</div>
		` ;

    }else return '';
}
function apiStatusHtml(as){

  let apiStatus_html =  '';
	let classStatud =  '';
	const codeSt = as.substring(0,3) ;
	const Msg = as.replace(/^\d+/,'') ;

    if(    codeSt <= 201 ) {

        apiStatus_html = 'Miraget service available.'
        classStatud = 'sec' ;

    } else {

        apiStatus_html = Msg
        classStatud = 'err' ;

    }
    return [apiStatus_html,classStatud] ;
}
function rightSide(){
	const currentUrl = location.href ;
	let worckabletarget = '' ;
	if(  ! isWorkable )
	worckabletarget = ` <div class="noInfotarget">
	Please choose your target and complete settings
	</div>` ;

	return ` <div id="postbox-container-1" class="postbox-container">
		<div id="side-sortables" class="meta-box-sortables ui-sortable">
			<div id="dashboard_quick_press" class="postbox closedss">
			<h2 class="hndle ui-sortable-handle">
			<span><span class="hide-if-no-js">Settings</span>
			<span class="hide-if-js">Your Recent Drafts</span></span>
			</h2>
        ${worckabletarget}
<div class="inside">
     ${errorPost()}
	<form name="post" action="${currentUrl}" method="post" id="quick-press" class="initial-form hide-if-no-js">

		<input type="hidden" value="${nonce}" name="m_none">
		<div class="selectOPtionBox">
			<span class="mcrmtitle">Sync On / Off </span>

			<span class="chck">
				<input ${ onOff === 1 ? 'checked' : ''} type="radio" value="1" name="on_off">
				On
		    </span>
			<span class="chck">
				<input ${ onOff === 0 ? 'checked' : ''}  type="radio" value="0" name="on_off">
				Off
		    </span>

		</div>

		<div class="input-text-wrap miraget-info" id="title-wrap">
			<label class="prompt" for="title" id="title-prompt-text">			 Miraget Token             </label>
			<input type="text" name="miraget_key" id="title" autocomplete="off" value="${miragetKey}">
		</div>
		<div class="selectOPtionBox">
			<span class="mcrmtitle">Rows to sync  </span>
			<span class="chck">
			<input   ${ rowsToSync  === 10 ? 'checked' : ''} type="radio" value="10" name="rows_to_sync" >			10			</span>
			<span class="chck"> <input ${ rowsToSync  === 20 ? 'checked' : ''} type="radio" value="20" name="rows_to_sync"> 20			</span>
			<span class="chck"> <input ${ rowsToSync  === 30 ? 'checked' : ''} type="radio" value="30" name="rows_to_sync"> 30			</span>
		</div>
		<div class="selectOPtionBox">
		<span class="mcrmtitle">Refresh every </span>
		${optionreFreshData()}
		</div>

		<div class="textarea-wrap" id="description-wrap">
			<div class="crmBox">
				<span class="mcrmtitle">Plugin Source </span>
				<span class="mcrmtitle2">OptInPanda</span>
			</div>
			<div class="selectOPtionBox">
				<span class="mcrmtitle">Target system</span>
				<span class="chck">  <input onChange="listOption(this,false)" ${ pluginTarget === 1 ? 'checked' : ''} type="radio" value="1" name="miraget_plugin_target" >	 Zoho    </span>
				<span class="chck">	 <input onChange="listOption(this,false)"  ${ pluginTarget === 2 ? 'checked' : ''} type="radio" value="2" name="miraget_plugin_target" > Salesmate	</span>
				<span class="chck">	 <input onChange="listOption(this,false)"  ${ pluginTarget === 3 ? 'checked' : ''} type="radio" value="3" name="miraget_plugin_target" > Insightly	</span>
			</div>
			<div class="listoptions" id="opList">
			  ${listOption(pluginTarget ,true)}
			</div>

		</div>

		<div class="textarea-wrap" id="description-wrap">
             <div class="nothing"> </div>
			<p class="submit miraget-submit">
			<input type="submit" name="save" id="save-post" class="button button-primary" value="Save All changes!">
			<br class="clear">
	   </p>
		</div>
	</form>
	</div>
</div>

</div>	</div>` ;
}
function listOption(index,load){

	let indexed ;
	if(load) indexed =  index.toString() ;
	else indexed = index.value ;
	//console.log(typeof index)
	let html_list = '' ;
	

  //console.log(pluginTargetVersion);
	//console.log("test")
	// zoho
   if( indexed === '1' ){
	html_list += `
	<div class="selectOPtionBox">
	  <span class="mcrmtitle">Update CRM</span>
		<span class="chck">
		<input   ${ updCrmRecord  === 1 ? 'checked' : ''} type="radio" value="1" name="upd_crm_record" >		Yes
	   </span>
	   <span class="chck"> <input ${ updCrmRecord  === 2 ? 'checked' : ''} type="radio" value="2" name="upd_crm_record"> 		No
	   </span>
	</div>
	<div class="selectOPtionBox">
	 <span class="mcrmtitle">API version  </span>
	 <span class="chck">	<input ${ pluginTargetVersion  === 1 ? 'checked' : ''} type="radio" value="1" name="miraget_plugin_zoho_vars" onchange="apiV1(this,false)"  >	API v1  </span>
	 <span class="chck">  <input ${ pluginTargetVersion  === 2 ? 'checked' : ''} type="radio" value="2" name="miraget_plugin_zoho_vars" onchange="apiV1(this,false)"  > 	API v2  </span>


	<div class="span" id="span">
		${apiV1(pluginTargetVersion,true)}
	</div>
		</div>
	`




}

	//salesforce
	else if( indexed === '2' )

	html_list = `
	<div class="m-sisforce">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<label class="prompt " for="user" id="title-prompt-text"> Token Id </label>
			<input  type="text" name="salesforce_user" value="${salesforceUser}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<label class="prompt " for="Password" id="title-prompt-text"> Secret Id </label>
			<input  type="text" name="salesforce_pass" value="${salesforcePass}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<label class="prompt" for="Salesforce" id="title-prompt-text"> Code </label>
			<input  type="text" name="salesforce_url" value="${salesforceUrl}">
		</div>
	</div>
` ;
//adding insightly 
else if(indexed === '3')
	html_list = `
	<div class="m-sisforce">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<label class="prompt " for="user" id="title-prompt-text"> Access Key </label>
			<input  type="text" name="insightly_access_key" value="${insightlyAccessKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<label class="prompt " for="Password" id="title-prompt-text"> Secret Key </label>
			<input  type="text" name="insightly_secret_key" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<label class="prompt" for="Salesforce" id="title-prompt-text"> Session Key</label>
			<input  type="text" name="insightly_session_key" value="${insightlySessionKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<label class="prompt" for="Salesforce" id="title-prompt-text"> Target Api</label>
			<input  type="text" name="insightly_target_api" value="${insightlyTargetApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<label class="prompt" for="Salesforce" id="title-prompt-text">  Api Tokenn</label>
			<input  type="text" name="insightly_token_api" value="${insightlyTokenApi}">
		</div>
	</div>
` ;


	else html_list = '';
	if(load) {
		return html_list ;
	}
	  else document.getElementById('opList').innerHTML = html_list ;

}




function apiV1(index, load){

	let indexe ;
	if(load) indexe =  index.toString() ;
	else indexe = index.value ;
	console.log(index);
	//console.log(typeof index)
	let html_list = '' ;

	//zoho v1
	if( indexe === '1' ){
	 html_list += `
				<div class="m-sisforce zohoLink">
					<div class="input-text-wrap miraget-info " id="title-wrap" style="margin:10px 0;">
						<label class="prompt mcrmtitle" for="token" id="title-prompt-text
						style="width: 84px;"> Zoho token </label>
						<input type="text" name="zoho_token" value="${zohoToken}">
					</div>
				</div>
				<a target="_blank" href="https://miraget.com/how-to-to-fill-zoho-api-credentials/">How to get Zoho token</a>
			`;
			
 }

	else if( indexe === '2' )
	 //zoho v2
		 html_list += `
 			</br></br>
		 	<div class=""> <a target="_blank" href="https://www.zoho.com/crm/help/api/v2/#oauth-request">Get Zoho Oauth credentials</a> 				</div>
			<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px  0;">
				<label class="prompt zoho-apiv2" for="title" id="title-prompt-text">			Client Id			</label>
				<input class="zoho-apiv2" type="text" name="client_id" id="title" autocomplete="off" value="${clientId}">

			</div>
			<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
				<label class="prompt zoho-apiv2" for="title" id="title-prompt-text"> 			Secret Id 			</label>
				<input class="zoho-apiv2" type="text" name="secret_id" id="title" autocomplete="off" value="${secretId}">
			</div>
			<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
				<label class="prompt zoho-apiv2" for="title" id="title-prompt-text"> 	Zoho	Code 			</label>
				<input class="zoho-apiv2" type="text" name="code" id="title" autocomplete="off" value="${codeZoho}">
			</div>
			<div class="selectOPtionBox" style="margin:10px 0;">
				<span class="mcrmtitle">Data center  </span>
				<span class="chck">
					<input ${ zohoDataCenter  === 1 ? 'checked' : ''} checked="" type="radio" value="1" name="zoho_datacenter"> 	.EU			</span>
				<span class="chck">
				 <input ${ zohoDataCenter  === 2 ? 'checked' : ''} type="radio" value="2" name="zoho_datacenter" > .CN			</span>
				<span class="chck">
				 <input ${ zohoDataCenter  === 3 ? 'checked' : ''} type="radio" value="3" name="zoho_datacenter" > .COM		</span>

		  </div>

   	`;
	//	document.getElementById('spanv1').style.display =  s ? 'block' : 'none' ;
	//	document.getElementById('spanv2').style.display =  s ? 'none' : 'block' ;

		else html_list = '';
		if(load) {
			return html_list ;
		}
		  else document.getElementById('span').innerHTML = html_list ;


}

function optionreFreshData(){
	let hrm = '' ;
	let selected = '' ;
	for (let index = 5; index <= 15; index += 5  ) {

		if( index === refresh_data ) selected = 'checked' ;
		else selected = '' ;
		//hrm += ' <option '+ selected +' value="' + index + '">'+ index +'</option>  '
		hrm += `
		<span class="chck">
			<input ${ selected } type="radio" value="${index}" name="miraget_plugin_refresh_data" >
			${index} minutes
		</span>
		` ;
	}



	return hrm ;
}


function emailActivity(){
	return `
	<div id="community-events" class="community-events" aria-hidden="false">
	<div class="activity-block">
		<p>
			<span id="community-events-location-message" aria-hidden="false">
			Last emails sent
			</span>

			<!--<button class="button-link community-events-toggle-location" aria-label="Edit city" aria-expanded="false" aria-hidden="false">
				<span class="dashicons dashicons-edit"></span>
			</button>-->
		</p>
	</div>
	<ul class="community-events-results activity-block last" aria-hidden="false">
	 ${emailRows()}

</ul>
</div>` ;
}


function emailRows(){

    if( emailes.length === 0 ) return '<p class="event event-wordcamp wp-clearfix no-e-s">No email sent !!</p>' ;
    let returnd = '' ;
	for (let index = 0; index <  emailes.length ; index++) {
		var date = new Date( emailes[index].time * 1000  );
       const t = date.toString().replace('GMT+0100 (Romance Standard Time)','') ;

		returnd += `
			<li class="event event-wordcamp wp-clearfix">
				<div class="event-info">
					<div class="dashicons event-icon" aria-hidden="true"></div>
					<div class="event-info-inner">
						<a class="event-title" href="#">${emailes[index].email}</a>
						<!--<span class="event-city">Granada, Granada, Spain</span>-->
					</div>
				</div>

				<div class="event-date-time">
					<!--<span class="event-date">Saturday, Nov 17, 2018</span>-->
					<span class="event-date">${t}</span>

				</div>
			</li>
		`;
	}

return returnd ;
}



function timesAdjust( time ){
}


Date.prototype.yyyymmdd = function( ) {
	var mm = this.getMonth() + 1; // getMonth() is zero-based
	var dd = this.getDate();

	return [this.getFullYear(),
			(mm>9 ? '' : '0') + mm,
			(dd>9 ? '' : '0') + dd
		   ].join('');
  };
 
