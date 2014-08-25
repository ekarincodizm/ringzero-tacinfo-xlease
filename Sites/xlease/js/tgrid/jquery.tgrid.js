(function($){
	$.extend({
		tgrid: new function() {
			this.defaults = {
				cssHeader: "header",
				css_asc: "headerSortUp",
				css_desc: "headerSortDown",
				sortInitialOrder: "asc",
				sortMultiSortKey: "shiftKey",
				sortList: [],
				headerList: [],
				sortOrder: ["asc","desc"]
			};
			
			function fixColumnWidth(table,$headers) {
				var c = table.config;
				if(c.widthFixed) {
					var colgroup = $('<colgroup>');
					$("tr:first td",table.tBodies[0]).each(function() {
						colgroup.append($('<col>').css('width',$(this).width()));
					});
					$(table).prepend(colgroup);
				};
			};
			
			function createOrderBy(arr) {
				var out = [];
				for (pole in arr) {
					out.push(pole + " " + arr[pole]);
				}	
				return out.join(", ");
			};
			
			function filtruj(event) {
				var el = event.target.id.replace("filtr_","");
				$("#"+el+" tbody").empty();
				var _curr_cache = Array();
				var klucz = $(this).val().toUpperCase();
				$(core.cache[el]).each(function(){
					for (pole in this) {
						if (config.allowed_cols_assoc[pole]) {
							if (this[pole].toUpperCase().indexOf(klucz) != -1) {
								_curr_cache.push(this);
								break;
							}
						}
					}
				});
				displayData(_curr_cache, el);
				setTableBasis("#"+el+" tbody");				
			};
			function displayData(arr, el, add_to_cache, obj) {
				config = obj;
				$(arr).each(function(){
					if (add_to_cache != null) {
						core.cache[config.element].push(this);
					}
					var row = $("<tr>");
					for (x in config.allowed_cols_assoc) {
						if (config.object.renderers && config.object.renderers[x])
						{
							row.append($("<td>"+config.object.renderers[x](this[x], this)+"</td>"));	
						}
						else
						{
							row.append($("<td>"+this[x]+"</td>"));
						}
					}
					if (!config.object.noEdit && config.object.editHref) {
						row.append($("<td align=\"center\"><a href=\""+config.object.editHref(this)+"\">edit</a></td>"));
					}
					if (!config.object.noCheckUsun && config.object.checkBoxName) {
						row.append($("<td align='center'><input type='checkbox' name='"+config.object.checkBoxName+"[]' value='"+this[config.object.colName]+"' /></td>"));
					}
					$("#"+el+" tbody").append(row);
				});	
			}					
			
			function im_done_here() {
				if (core.after_callback != null) {
					e = core.after_callback;
					core.after_callback = null;
					e();
				}
			}					
			
			function fetchData(obj) {
				if (obj != null) {
					config = obj.config;
				}
				//console.dir(config);
				config.object.config = this.config;
				//console.dir(config.object.config);
				var fetchParams = config.object.__create_fetch_params();
				//console.dir(config.object);
				fetchParams.params.order_by = createOrderBy(config.object.sortList);
				fetchParams.params.config = config.object.id;
				//fetchParams.params.config.element = this.config.element;
				//console.info(this.config.element);
				$.post(fetchParams.url, fetchParams.params, dataFetched, "json");
				startProgress();
			};
			function dataFetched(o) {
				//console.dir(o);
				config = eval(o.config+".config");
				//console.dir(config);
				stopProgress();
				//console.info(config.element);
				$("#"+config.element+" tbody").empty();
				$("#"+config.element+" tfoot").empty();
				core.cache[config.element] = [];
				if (o != -1 && o.out != -1) {
					//console.dir(ts);
					displayData(o.out, config.element, 1, config);
					//konstrukcja stronicowania.	
					var txt = "Pages: ";
					var na_strone = "Records per page: ";
					for (var i = 0;i<Math.ceil(o.count/config.object.per_page); i++) {
						if (i == config.object.page) {
							txt+=(i+1);
						}
						else {
							txt+='<a href="javascript:'+config.object.id+'.setPage('+i+');">'+(i+1)+'</a>';
						}
						txt+="&nbsp;";
					}
					if (!config.object.per_page_options) {
						config.object.per_page_options = [5, 15,20,30,50,100];
					}
					$(config.object.per_page_options).each(function() {
						na_strone += '<a href="javascript:'+config.object.id+'.setPerPage('+this+');">'+this+'</a>&nbsp;';
					});
					$("#"+config.element+" tfoot")
						.append($("<tr>")
							.append($("<td>"+txt+"</td>"))
							.append($("<td colspan=\"5\">"+na_strone+"</td>"))
							);
					setTableBasis("#"+config.element+" tbody");
				}
				else {
					if (o.blad) {
						sqlError(o.blad);
					}
				}
				im_done_here();
			};
			
			function constructHeaders(table) {
				//for(var i = 0; i < table.tHead.rows.length; i++) { tableHeadersRows[i]=0; };
				$tableHeaders = $("thead th",table);
				$tableHeaders.each(function(index) {
					this.count = 0;
					this.column = table.config.object.__defined_cols[index];
					this.index = index;
					this.order = "asc"
					
					if(table.config.object.__disabled_cols && table.config.object.__disabled_cols[index] == true) 
						this.sortDisabled = true;
					
					if(!this.sortDisabled) {
						$(this).addClass(table.config.cssHeader);
					}
					table.config.headerList[index]= this;
				});
				
				return $tableHeaders;
			};
			function setOrder(order) {
				if (order == "asc") {
					return "desc";
				}
				else return "asc";
			};
			
			this.construct = function(settings) {
				return this.each(function(){
					core.filtr = "";
					//console.info("START");
					if(!this.tHead || !this.tBodies) return;
					
					var $this, $document,$headers, cache, config, shiftDown = 0, sortOrder;
					
					this.config = {element: this.id,sortList: {}};
					
					config = $.extend(this.config, $.tgrid.defaults, settings);
					config.allowed_cols_assoc = gen_assoc_array(config.object.__defined_cols);
					if (!config.object.sortList) {
						config.object.sortList = {};
					}
					//console.dir(config.allowed_cols_assoc);
					core.cache = {};
					order_by = [];
					$(this).append($("<tfoot>"));
					// store common expression for speed					
					$this = $(this);
					$headers = constructHeaders(this);
					
					$headers.click(function(e){
						$headers.removeClass(config.css_asc).removeClass(config.css_desc);
						if (this.column) {
							var col = this.column;
							//pomijamy te po ktorych nie mozna sortowac.
							if (!e[config.sortMultiSortKey]) {
								//jesli user kliknal tylko na jedna kolumne
								order_by = [];
								config.object.sortList = {};
								$headers.each(function(){
									if (this.column != col) {
										this.order = "asc";
										//$(this).removeClass(config.css_asc).removeClass(config.css_desc);
									}
								});
							}
							config.object.sortList[this.column] = this.order;
							$headers.each(function(){
								if (config.object.sortList[this.column]) {
									$(this).addClass(eval("config.css_"+setOrder(config.object.sortList[this.column])));
								}
							});
							this.order = setOrder(this.order);
							config.object.order_by = createOrderBy(config.object.sortList);
							this.config = config;
							fetchData(this);
						}
					});
				
					$this.bind("sortOn",function(e, what, func) {
						$(what).each(function(){
							if ($headers[this[0]]) {
								config.object.sortList[$headers[this[0]].column] = config.sortOrder[this[1]];
								$($headers[this[0]]).addClass(eval("config.css_"+setOrder(config.sortOrder[this[1]])));
								$headers[this[0]].order = setOrder(config.sortOrder[this[1]]);
							}
						});
						if (typeof(func) == "function") {
							core.after_callback = func;
						}
						fetchData(this);
					}).bind("refresh",function() {
						fetchData(this);
					});
				});
			};
		}
	});
	
	$.fn.extend({
        	tgrid: $.tgrid.construct
	});
	
	var ts = $.tgrid;
	
})(jQuery);
