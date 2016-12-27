function logoElementlogo()
{
	if (navigator.userAgent.indexOf('Mozilla/3') != -1)
	{
	document.write('Sorry, since you are using an old version of Netscape, you may not be able to access all the pages in this Web site.');
	}
	else
	{
	var strHTML = '';
	strHTML += '<a href="' + strRelativePathToRoot + 'index.html" target="" >';
	strHTML += '<img src="' + strRelativePathToRoot + '/uploads/images/logo_logoA.jpg?1291342426140" width="635" height="94" alt="" border="0" >';
	strHTML += '</a>';
	document.write(strHTML);
	}
}
function netscapeDivChecklogo()
{
	var strAppName = navigator.appName;
	var appVer = parseFloat(navigator.appVersion);
	if ( (strAppName == 'Netscape') &&
	(appVer >= 4.0 && appVer < 5) ) { document.write('</DIV>');
	}
}

logoElementlogo();
netscapeDivChecklogo();
