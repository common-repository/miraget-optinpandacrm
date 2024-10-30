var html = '' ,
currentPage = '' ;
 

function render(){
    html += '<div class="MiragetGenerator-main-cont wrap">' ;
    subMenu() ;
     miragetPage();

    html += '</div>' ;
    document.write( html ) ;
}
function subMenu(){


       /* html +=  `<div class="updated notice topbarMiraget">
            <p>
                ${ iconSubtitle( currentPage ) }
                <b> ${pageTitle} </b> 
            </p>
            <div class="apiStatus">
                Api status : <span class="${classStatud}">${apiStatus_html}
                </span>
            </div>
        </div>` ;*/
 
}
function iconSubtitle( page ){

    let icon = '' ;

    if( page == 'main')
      icon =  '<span class="dashicons dashicons-admin-home"></span>' ;

    if( page == 'settings')
      icon =  '<span class="dashicons dashicons-admin-generic"></span>' ;

    return icon ;

}
