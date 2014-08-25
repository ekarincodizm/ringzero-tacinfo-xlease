function getParams(el)
{
	var out = {};
	$(el)
	.find("input:checkbox:checked, input[type='text'], input[type='hidden'], input[type='password'], input[type='submit'], option[selected], textarea")
	.each(function()
	{
		out[ this.name || this.id || this.parentNode.name || this.parentNode.id ] = this.value; 
	}); 
	return out;
}

function enableFiltering()
{
	$("[id$='_filter']").each(function(){
		id = this.id.split("_");
		$(this).keyup(eval(id[0]+".tryRefresh"));
	});
}

function setTableBasis(e)
{
	$(e+" tr").mouseover(function(){ $(this).addClass("over"); }).mouseout(function() { $(this).removeClass("over"); });
	$(e+" tr:even").addClass("odd");
	$(e+" tr").click(function(event){
		if ($(event.target).is("input")) {} else {
			$(this).children("td").children("input [type='checkbox']").each(function(){
				$(this).check("toggle");
			});
		}
	});

}

function showProgress() {
	if (core.showProgress == 1) {
		/*$.blockUI({ message: $("#prosze_czekac"), applyPlatformOpacityRules:false});*/
	}
}
function startProgress() {
	core.showProgress = 1;
	setTimeout("showProgress()",2000);
}

function stopProgress() {
	core.showProgress = 0;
	/*$.unblockUI();*/
}

function gen_assoc_array(a)
{
	var out = {};
	for (var i=0;i<a.length;i++)
	{
		out[a[i]] = 1;
	}
	return out;
}

function jsModel() {
	this.asc_desc = ["asc","desc"];
	this.per_page = 15;
	this.page = 0;
	if (!this.colName)
	{
		this.colName = "id";
	}
	if (!this.rf)
	{
		this.rf = function(f)
		{
			if (!this.element)
			{
				this.element = this.id+"_table";
			}
			//console.info(this.element);
			$("#"+this.element).trigger("sortOn", [this.sortOrder, f]);
		}	
		
	}
	if (!this.config)
	{
		this.config = {};
	}
	if (!this.sortOrder)
	{
		this.sortOrder = [[0,0]];
	}
	if (!this.order_by)
	{
		this.order_by = [];
	}
	this.doRefresh = function(obj)
	{
		//console.info(obj.waitRefresh);
		if (obj.waitRefresh <= 0)
		{
			obj.rf();
			obj.lock = 0;
		}
		else
		{
			obj.waitRefresh-=300;
			setTimeout(function(){obj.doRefresh(obj)}, 300);
		}
	}	
	this.refreshHook = function()
	{
		this.waitRefresh = 1000;
		if (!this.lock || this.lock == 0)
		{
			this.lock = 1;
			this.doRefresh(this);
		}		
	}
	this.tryRefresh = function()
	{
		id = this.id.split("_");
		eval(id[0]+".refreshHook()");
	}
	this.__create_fetch_params = function() 
	{
		if (!this.funkcja)
		{
			this.funkcja = "CRUD";
		}
		if (!this.filter)
		{
			if ($("#"+this.id+"_filter"))
			{
				this.filter = "#"+this.id+"_filter";
			}
		}
		if (this.__other_params && typeof(this.__other_params) == "function") 
		{
			return {url: core.prefix, params: $.extend(this.__other_params(),{"page":this.page,"per_page":this.per_page, "filter": $(this.filter).val()})};
		}
		return {url: core.prefix, params: {"page":this.page,"per_page":this.per_page,"filter": $(this.filter).val()}};
	};
	
	this.setPage = function(x) 
	{
		if (!this.element)
		{
			this.element = this.id+"_table";
		}		
		this.page = x;
		$("#"+this.element).trigger("refresh");
	};
	
	this.set = function(param, value)
	{
		eval("this."+param+" = "+value);
	};
	this.setPerPage = function(x) 
	{
		if (!this.element)
		{
			this.element = this.id+"_table";
		}				
		this.per_page = x;
		this.page = 0;
		$("#"+this.element).trigger("refresh");
	};
	
	this.submitForm = function(form,p,f) 
	{
		if (!this.funkcja)
		{
			this.funkcja = "CRUD";
		}		
		if (form != null) 
		{
			var params = getParams(form);
		}
		else 
		{
			var params = {};
		}
		if (this.className) 
		{
			params.className = this.className;
		}
		if (this.funkcja) 
		{
			params.funkcja = this.funkcja;
		}
		params.CRUD = "list";
		$.extend(params,p);
		if (form != null) 
		{
			$.post($(form)[0].action,params,f,"json");
		}
		else 
		{
			$.post(core.prefix+"index.php",params,f,"json");
		}
	}
	
	this.dodaj = function(czysc)
	{
		$(".crud").removeClass("crud_active").hide();
		$("#form_"+this.id).show();
		if (czysc != false)
		{
			$("#form_"+this.id+" input[type='text'], input[type='password'], input[type='hidden']").val("");
		}
	}
	
	this.edytuj = function(id)
	{
		params = {"config":this.id, "id":id}
		$.post(core.prefix, params, function(out){
			eval(out.config+".setActiveCRUD(\"edytuj\");");
			$("#form_"+out.config+" form")
			.find("input, textarea, select")
			.each(function(){
				if (this.name != "")
				{
					elm = this.name.replace(/\[/g,"[\"").replace(/\]/g,"\"]").replace("rekord","[\"rekord\"]");
					try
					{
						v = eval("out"+elm);
						if (this.type == "text" || this.type == "hidden" || this.type == "textarea")
						{
							if (this.type == "textarea")
							{
								$(this).html(v);
								$(this).wysiwyg('setContent',v);
							}
							else
							{
								$(this).val(v);
							}
						}
						else
						{
							if (this.type == "checkbox" && v != null)
							{
								this.checked = true;
							}
							if (this.type == "select-one" && v != null)
							{
								$(this).children("option").each(function(){
									if (this.value == v) {
										$(this).attr("selected","selected");
									}
								});
							}
						}
					}
					catch(err)
					{
						//brak pola
					}
				}
			});
			try
			{
				eval(out.config+".hooks[\"edit\"](out)");
			}
			catch(err)
			{
				//brak funkcji
			}
		}, "json");
	}
	
	this.czyscPola = function()
	{
		$("#form_"+this.id+" form")
		.find("input[type='text'], input[type='password'], input[type='hidden'], textarea")
		.val("");
		$("#form_"+this.id+" form")
		.find("textarea").each(function(){
			$(this).html("");
			$(this).wysiwyg('setContent',"");
		});
		$("#form_"+this.id+" form")
		.find("input[type='checkbox']").each(function(){ this.checked = false; });
	}
	this.anulujZapisz = function(czysc)
	{
		$(".crud").each(function(){
			if ($(this).hasClass("list"))
			{
				$(this).addClass("crud_active").show();
			}
			else
			{
				$(this).hide();
			}
		});
		if (czysc != false)
		{
			this.czyscPola();
		}
	}
	
	this.CRUD = function()
	{
		id = $(this).parent().attr("id").split("_")[1];
		params = $.extend({"CRUD":"save","config":id}, getParams(this));
		startProgress();
		$.post(core.prefix,params, eval(id+".after_CRUD"), "json");
		return false;
	}
	
	this.after_CRUD = function(out)
	{
		stopProgress();
		eval(out.config+".CRUD_COMPLETE(out)");
	}
	
	this.setActiveCRUD = function(x)
	{
		$(".crud").hide();
		$("[class*='crud "+x+"']").show();
	}
	
	this.usun = function(kl)
	{
		if ($("[name='"+kl+"[]'][@checked]").length)
		{
			if (confirm("Czy napewno usunąć zaznaczone rekordy?"))
			{
				params = {"CRUD":"delete","config":this.id, "tryb":"modelFunction","className":this.className,"funkcja":"CRUD","do_us":lista_cb(kl+"[]")}
				$.post(core.prefix, params, this.after_CRUD, "json");
			}
		}
		else
		{
			alert("Proszę zaznaczyć rekordy do usunięcia!");
		}
	}
}

$.fn.check = function(mode) {
	var mode = mode || 'on'; // if mode is undefined, use 'on' as default
	return this.each(function() {
		switch(mode) {
		case 'on':
			this.checked = true;
			break;
		case 'off':
			this.checked = false;
			break;
		case 'toggle':
			this.checked = !this.checked;
			break;
		}
	});
};

function sendRequest(params,func) {
	params = $.extend(params, {"tryb":"modelFunction"});
	return $.post(core.prefix,params,func,"json");
}
