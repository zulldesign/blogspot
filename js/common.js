function js_check_all(formobj)
{
	exclude = new Array();
	exclude[0] = 'keepattachments';
	exclude[1] = 'allbox';
	exclude[2] = 'removeall';
	js_toggle_all(formobj, 'checkbox', '', exclude, formobj.allbox.checked);
}

function js_toggle_all(formobj, formtype, option, exclude, setto)
{
	for (var i =0; i < formobj.elements.length; i++)
	{
		var elm = formobj.elements[i];
		if (elm.type == formtype)
		{
			switch (formtype)
			{
				case 'radio':
					if (elm.value == option) 
					{
						elm.checked = setto;
					}
				break;
				case 'select-one':
					elm.selectedIndex = setto;
				break;
				default:
					elm.checked = setto;
				break;
			}
		}
	}
}

function imposeMaxLength(Object, MaxLen)
				{
				  return (Object.value.length <= MaxLen);
				}