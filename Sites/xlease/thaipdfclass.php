<?php
require('nw/join_payment/extensions/fpdf16/fpdf.php');
//include_once('Classes/tcpdf/tcpdf.php');
if(!class_exists('ThaiPDF')){
	define('ThaiPDFVersion','1.00T');
	class ThaiPDF extends FPDF{
		//Private properties
		var $page;               //current page number
		var $n;                  //current object number
		var $offsets;            //array of object offsets
		var $buffer;             //buffer holding in-memory PDF
		var $pages;              //array containing pages
		var $state;              //current document state
		var $compress;           //compression flag
		var $DefOrientation;     //default orientation
		var $CurOrientation;     //current orientation
		var $OrientationChanges; //array indicating orientation changes
		var $k;                  //scale factor (number of points in user unit)
		var $fwPt,$fhPt;         //dimensions of page format in points
		var $fw,$fh;             //dimensions of page format in user unit
		var $wPt,$hPt;           //current dimensions of page in points
		var $w,$h;               //current dimensions of page in user unit
		var $lMargin;            //left margin
		var $tMargin;            //top margin
		var $rMargin;            //right margin
		var $bMargin;            //page break margin
		var $cMargin;            //cell margin
		var $x,$y;               //current position in user unit for cell positioning
		var $lasth;              //height of last cell printed
		var $LineWidth;          //line width in user unit
		var $CoreFonts;          //array of standard font names
		var $fonts;              //array of used fonts
		var $FontFiles;          //array of font files
		var $diffs;              //array of encoding differences
		var $images;             //array of used images
		var $PageLinks;          //array of links in pages
		var $links;              //array of internal links
		var $FontFamily;         //current font family
		var $FontStyle;          //current font style
		var $underline;          //underlining flag
		var $CurrentFont;        //current font info
		var $FontSizePt;         //current font size in points
		var $FontSize;           //current font size in user unit
		var $DrawColor;          //commands for drawing color
		var $FillColor;          //commands for filling color
		var $TextColor;          //commands for text color
		var $ColorFlag;          //indicates whether fill and text colors are different
		var $ws;                 //word spacing
		var $AutoPageBreak;      //automatic page breaking
		var $PageBreakTrigger;   //threshold used to trigger page breaks
		var $InFooter;           //flag set when processing footer
		var $ZoomMode;           //zoom display mode
		var $LayoutMode;         //layout display mode
		var $title;              //title
		var $subject;            //subject
		var $author;             //author
		var $keywords;           //keywords
		var $creator;            //creator
		var $AliasNbPages;       //alias for total number of pages
		var $PDFVersion;         //PDF version number
		var $HTitle;
		var $HShowPNo;
		var $HAlign;
		var $HAdd;
		var $VTitle;
		var $VShowPNo;
		var $VAlign;
		var $VAdd;
		var $tmpFiles = array(); 
/*******************************************************************************
*                               Public methods                                 *
*******************************************************************************/
		function ThaiPDF($orientation='P',$unit='mm',$format='A4'){
			//Some checks
			$this->_dochecks();
			//Initialization of properties
			$this->page=0;
			$this->n=2;
			$this->buffer='';
			$this->pages=array();
			$this->OrientationChanges=array();
			$this->state=0;
			$this->fonts=array();
			$this->FontFiles=array();
			$this->diffs=array();
			$this->images=array();
			$this->links=array();
			$this->InFooter=false;
			$this->lasth=0;
			$this->FontFamily='';
			$this->FontStyle='';
			$this->FontSizePt=12;
			$this->underline=false;
			$this->DrawColor='0 G';
			$this->FillColor='0 g';
			$this->TextColor='0 g';
			$this->ColorFlag=false;
			$this->ws=0;
			//Standard fonts
			$this->CoreFonts=array('courier'=>'Courier','courierB'=>'Courier-Bold','courierI'=>'Courier-Oblique','courierBI'=>'Courier-BoldOblique',
													'helvetica'=>'Helvetica','helveticaB'=>'Helvetica-Bold','helveticaI'=>'Helvetica-Oblique','helveticaBI'=>'Helvetica-BoldOblique',
													'times'=>'Times-Roman','timesB'=>'Times-Bold','timesI'=>'Times-Italic','timesBI'=>'Times-BoldItalic',
													'symbol'=>'Symbol','zapfdingbats'=>'ZapfDingbats');
			//Scale factor
			if($unit=='pt')$this->k=1;
			elseif($unit=='mm')$this->k=72/25.4;
			elseif($unit=='cm')$this->k=72/2.54;
			elseif($unit=='in')	$this->k=72;
			else	$this->Error('Incorrect unit: '.$unit);
			//Page format
			if(is_string($format))	{
				$format=strtolower($format);
				if($format=='a3')	$format=array(841.89,1190.55);
				elseif($format=='a4') $format=array(595.28,841.89);
                elseif($format=='a4half') $format=array(595.28,420.94);
				elseif($format=='a5') $format=array(420.94,595.28);
                elseif($format=='f4') $format=array(595.28,933.9);
				elseif($format=='letter') $format=array(612,792);
				elseif($format=='legal') $format=array(612,1008);
				//elseif($format=='') $format=array(581.1,396.9);
				//elseif($format=='slip') $format=array(575.43,790.87);
				elseif($format=='slip_av') $format=array(578.27,396.85);
				elseif($format=='letter_av') $format=array(663.31,306.14);
				else $this->Error('Unknown page format: '.$format);
				$this->fwPt=$format[0];
				$this->fhPt=$format[1];
			}
			else	{
				$this->fwPt=$format[0]*$this->k;
				$this->fhPt=$format[1]*$this->k;
			}
			$this->fw=$this->fwPt/$this->k;
			$this->fh=$this->fhPt/$this->k;
			//Page orientation
			$orientation=strtolower($orientation);
			if($orientation=='p' || $orientation=='portrait')	{
				$this->DefOrientation='P';
				$this->wPt=$this->fwPt;
				$this->hPt=$this->fhPt;
			}
			elseif($orientation=='l' || $orientation=='landscape'){
				$this->DefOrientation='L';
				$this->wPt=$this->fhPt;
				$this->hPt=$this->fwPt;
			}
			else	$this->Error('Incorrect orientation: '.$orientation);
			$this->CurOrientation=$this->DefOrientation;
			$this->w=$this->wPt/$this->k;
			$this->h=$this->hPt/$this->k;
			//Page margins (1 cm)
			$margin=28.35/$this->k;
			$this->SetMargins($margin,$margin);
			//Interior cell margin (1 mm)
			$this->cMargin=$margin/10;
			//Line width (0.2 mm)
			$this->LineWidth=.567/$this->k;
			//Automatic page break
			$this->SetAutoPageBreak(true,2*$margin);
			//Full width display mode
			$this->SetDisplayMode('fullwidth');
			//Enable compression
			$this->SetCompression(true);
			//Set default PDF version number
			$this->PDFVersion='1.3';
		}
		

/**
	 * Set the same internal Cell padding for top, right, bottom, left-
	 * @param $pad (float) internal padding.
	 * @public
	 * @since 2.1.000 (2008-01-09)
	 * @see getCellPaddings(), setCellPaddings()
	 */
	protected  function SetCellPadding($pad) {
		if ($pad >= 0) {
			$this->cell_padding['L'] = $pad;
			$this->cell_padding['T'] = $pad;
			$this->cell_padding['R'] = $pad;
			$this->cell_padding['B'] = $pad;
		}
	}
	
	protected function getGraphicVars() {
		$grapvars = array(
			'FontFamily' => $this->FontFamily,
			'FontStyle' => $this->FontStyle,
			'FontSizePt' => $this->FontSizePt,
			'rMargin' => $this->rMargin,
			'lMargin' => $this->lMargin,
			'cell_padding' => $this->cell_padding,
			'cell_margin' => $this->cell_margin,
			'LineWidth' => $this->LineWidth,
			'linestyleWidth' => $this->linestyleWidth,
			'linestyleCap' => $this->linestyleCap,
			'linestyleJoin' => $this->linestyleJoin,
			'linestyleDash' => $this->linestyleDash,
			'textrendermode' => $this->textrendermode,
			'textstrokewidth' => $this->textstrokewidth,
			'DrawColor' => $this->DrawColor,
			'FillColor' => $this->FillColor,
			'TextColor' => $this->TextColor,
			'ColorFlag' => $this->ColorFlag,
			'bgcolor' => $this->bgcolor,
			'fgcolor' => $this->fgcolor,
			'htmlvspace' => $this->htmlvspace,
			'listindent' => $this->listindent,
			'listindentlevel' => $this->listindentlevel,
			'listnum' => $this->listnum,
			'listordered' => $this->listordered,
			'listcount' => $this->listcount,
			'lispacer' => $this->lispacer,
			'cell_height_ratio' => $this->cell_height_ratio,
			'font_stretching' => $this->font_stretching,
			'font_spacing' => $this->font_spacing,
			// extended
			'lasth' => $this->lasth,
			'tMargin' => $this->tMargin,
			'bMargin' => $this->bMargin,
			'AutoPageBreak' => $this->AutoPageBreak,
			'PageBreakTrigger' => $this->PageBreakTrigger,
			'x' => $this->x,
			'y' => $this->y,
			'w' => $this->w,
			'h' => $this->h,
			'wPt' => $this->wPt,
			'hPt' => $this->hPt,
			'fwPt' => $this->fwPt,
			'fhPt' => $this->fhPt,
			'page' => $this->page,
			'current_column' => $this->current_column,
			'num_columns' => $this->num_columns
			);
		return $grapvars;
	}		

	protected function checkPageRegions($h, $x, $y) {
		// set default values
		if ($x === '') {
			$x = $this->x;
		}
		if ($y === '') {
			$y = $this->y;
		}
		if (empty($this->page_regions)) {
			// no page regions defined
			return array($x, $y);
		}
		if (empty($h)) {
			$h = ($this->FontSize * $this->cell_height_ratio) + $this->cell_padding['T'] + $this->cell_padding['B'];
		}
		// check for page break
		if ($this->checkPageBreak($h, $y)) {
			// the content will be printed on a new page
			$x = $this->x;
			$y = $this->y;
		}
		if ($this->num_columns > 1) {
			if ($this->rtl) {
				$this->lMargin = $this->columns[$this->current_column]['x'] - $this->columns[$this->current_column]['w'];
			} else {
				$this->rMargin = $this->w - $this->columns[$this->current_column]['x'] - $this->columns[$this->current_column]['w'];
			}
		} else {
			if ($this->rtl) {
				$this->lMargin = $this->original_lMargin;
			} else {
				$this->rMargin = $this->original_rMargin;
			}
		}
		// adjust coordinates and page margins
		foreach ($this->page_regions as $regid => $regdata) {
			if ($regdata['page'] == $this->page) {
				// check region boundaries
				if (($y > ($regdata['yt'] - $h)) AND ($y <= $regdata['yb'])) {
					// Y is inside the region
					$minv = ($regdata['xb'] - $regdata['xt']) / ($regdata['yb'] - $regdata['yt']); // inverse of angular coefficient
					$yt = max($y, $regdata['yt']);
					$yb = min(($yt + $h), $regdata['yb']);
					$xt = (($yt - $regdata['yt']) * $minv) + $regdata['xt'];
					$xb = (($yb - $regdata['yt']) * $minv) + $regdata['xt'];
					if ($regdata['side'] == 'L') { // left side
						$new_margin = max($xt, $xb);
						if ($this->lMargin < $new_margin) {
							if ($this->rtl) {
								// adjust left page margin
								$this->lMargin = $new_margin;
							}
							if ($x < $new_margin) {
								// adjust x position
								$x = $new_margin;
								if ($new_margin > ($this->w - $this->rMargin)) {
									// adjust y position
									$y = $regdata['yb'] - $h;
								}
							}
						}
					} elseif ($regdata['side'] == 'R') { // right side
						$new_margin = min($xt, $xb);
						if (($this->w - $this->rMargin) > $new_margin) {
							if (!$this->rtl) {
								// adjust right page margin
								$this->rMargin = ($this->w - $new_margin);
							}
							if ($x > $new_margin) {
								// adjust x position
								$x = $new_margin;
								if ($new_margin > $this->lMargin) {
									// adjust y position
									$y = $regdata['yb'] - $h;
								}
							}
						}
					}
				}
			}
		}
		return array($x, $y);
	}
	
	protected function inPageBody() {
		return (($this->InHeader === false) AND ($this->InFooter === false));
	}
	protected function checkPageBreak($h=0, $y='', $addpage=true) {
		if ($this->empty_string($y)) {
			$y = $this->y;
		}
		$current_page = $this->page;
		if ((($y + $h) > $this->PageBreakTrigger) AND ($this->inPageBody()) AND ($this->AcceptPageBreak())) {
			if ($addpage) {
				//Automatic page break
				$x = $this->x;
				$this->AddPage($this->CurOrientation);
				$this->y = $this->tMargin;
				$oldpage = $this->page - 1;
				if ($this->rtl) {
					if ($this->pagedim[$this->page]['orm'] != $this->pagedim[$oldpage]['orm']) {
						$this->x = $x - ($this->pagedim[$this->page]['orm'] - $this->pagedim[$oldpage]['orm']);
					} else {
						$this->x = $x;
					}
				} else {
					if ($this->pagedim[$this->page]['olm'] != $this->pagedim[$oldpage]['olm']) {
						$this->x = $x + ($this->pagedim[$this->page]['olm'] - $this->pagedim[$oldpage]['olm']);
					} else {
						$this->x = $x;
					}
				}
			}
			return true;
		}
		if ($current_page != $this->page) {
			// account for columns mode
			return true;
		}
		return false;
	}
	
	protected function fitBlock($w, $h, $x, $y, $fitonpage=false) {
		if ($w <= 0) {
			// set maximum width
			$w = ($this->w - $this->lMargin - $this->rMargin);
		}
		if ($h <= 0) {
			// set maximum height
			$h = ($this->PageBreakTrigger - $this->tMargin);
		}
		// resize the block to be vertically contained on a single page or single column
		if ($fitonpage OR $this->AutoPageBreak) {
			$ratio_wh = ($w / $h);
			if ($h > ($this->PageBreakTrigger - $this->tMargin)) {
				$h = $this->PageBreakTrigger - $this->tMargin;
				$w = ($h * $ratio_wh);
			}
			// resize the block to be horizontally contained on a single page or single column
			if ($fitonpage) {
				$maxw = ($this->w - $this->lMargin - $this->rMargin);
				if ($w > $maxw) {
					$w = $maxw;
					$h = ($w / $ratio_wh);
				}
			}
		}
		// Check whether we need a new page or new column first as this does not fit
		$prev_x = $this->x;
		$prev_y = $this->y;
		if ($this->checkPageBreak($h, $y) OR ($this->y < $prev_y)) {
			$y = $this->y;
			if ($this->rtl) {
				$x += ($prev_x - $this->x);
			} else {
				$x += ($this->x - $prev_x);
			}
			$this->newline = true;
		}
		// resize the block to be contained on the remaining available page or column space
		if ($fitonpage) {
			$ratio_wh = ($w / $h);
			if (($y + $h) > $this->PageBreakTrigger) {
				$h = $this->PageBreakTrigger - $y;
				$w = ($h * $ratio_wh);
			}
			if ((!$this->rtl) AND (($x + $w) > ($this->w - $this->rMargin))) {
				$w = $this->w - $this->rMargin - $x;
				$h = ($w / $ratio_wh);
			} elseif (($this->rtl) AND (($x - $w) < ($this->lMargin))) {
				$w = $x - $this->lMargin;
				$h = ($w / $ratio_wh);
			}
		}
		return array($w, $h, $x, $y);
	}
	
	public function SetDrawColorArray($color, $ret=false) {
		if (is_array($color)) {
			$color = array_values($color);
			$r = isset($color[0]) ? $color[0] : -1;
			$g = isset($color[1]) ? $color[1] : -1;
			$b = isset($color[2]) ? $color[2] : -1;
			$k = isset($color[3]) ? $color[3] : -1;
			$name = isset($color[4]) ? $color[4] : ''; // spot color name
			if ($r >= 0) {
				return $this->SetDrawColor($r, $g, $b, $k, $ret, $name);
			}
		}
		return '';
	}
	
	public function SetTextColorArray($color, $ret=false) {
		if (is_array($color)) {
			$color = array_values($color);
			$r = isset($color[0]) ? $color[0] : -1;
			$g = isset($color[1]) ? $color[1] : -1;
			$b = isset($color[2]) ? $color[2] : -1;
			$k = isset($color[3]) ? $color[3] : -1;
			$name = isset($color[4]) ? $color[4] : ''; // spot color name
			if ($r >= 0) {
				$this->SetTextColor($r, $g, $b, $k, $ret, $name);
			}
		}
	}
	
	protected function setGraphicVars($gvars, $extended=false) {
		$this->FontFamily = $gvars['FontFamily'];
		$this->FontStyle = $gvars['FontStyle'];
		$this->FontSizePt = $gvars['FontSizePt'];
		$this->rMargin = $gvars['rMargin'];
		$this->lMargin = $gvars['lMargin'];
		$this->cell_padding = $gvars['cell_padding'];
		$this->cell_margin = $gvars['cell_margin'];
		$this->LineWidth = $gvars['LineWidth'];
		$this->linestyleWidth = $gvars['linestyleWidth'];
		$this->linestyleCap = $gvars['linestyleCap'];
		$this->linestyleJoin = $gvars['linestyleJoin'];
		$this->linestyleDash = $gvars['linestyleDash'];
		$this->textrendermode = $gvars['textrendermode'];
		$this->textstrokewidth = $gvars['textstrokewidth'];
		$this->DrawColor = $gvars['DrawColor'];
		$this->FillColor = $gvars['FillColor'];
		$this->TextColor = $gvars['TextColor'];
		$this->ColorFlag = $gvars['ColorFlag'];
		$this->bgcolor = $gvars['bgcolor'];
		$this->fgcolor = $gvars['fgcolor'];
		$this->htmlvspace = $gvars['htmlvspace'];
		$this->listindent = $gvars['listindent'];
		$this->listindentlevel = $gvars['listindentlevel'];
		$this->listnum = $gvars['listnum'];
		$this->listordered = $gvars['listordered'];
		$this->listcount = $gvars['listcount'];
		$this->lispacer = $gvars['lispacer'];
		$this->cell_height_ratio = $gvars['cell_height_ratio'];
		$this->font_stretching = $gvars['font_stretching'];
		$this->font_spacing = $gvars['font_spacing'];
		if ($extended) {
			// restore extended values
			$this->lasth = $gvars['lasth'];
			$this->tMargin = $gvars['tMargin'];
			$this->bMargin = $gvars['bMargin'];
			$this->AutoPageBreak = $gvars['AutoPageBreak'];
			$this->PageBreakTrigger = $gvars['PageBreakTrigger'];
			$this->x = $gvars['x'];
			$this->y = $gvars['y'];
			$this->w = $gvars['w'];
			$this->h = $gvars['h'];
			$this->wPt = $gvars['wPt'];
			$this->hPt = $gvars['hPt'];
			$this->fwPt = $gvars['fwPt'];
			$this->fhPt = $gvars['fhPt'];
			$this->page = $gvars['page'];
			$this->current_column = $gvars['current_column'];
			$this->num_columns = $gvars['num_columns'];
		}
		$this->_out(''.$this->linestyleWidth.' '.$this->linestyleCap.' '.$this->linestyleJoin.' '.$this->linestyleDash.' '.$this->DrawColor.' '.$this->FillColor.'');
		if (!$this->empty_string($this->FontFamily)) {
			$this->SetFont($this->FontFamily, $this->FontStyle, $this->FontSizePt);
		}
	}
	
	function empty_string($str) {
		return (is_null($str) OR (is_string($str) AND (strlen($str) == 0)));
	}
	
	function write1DBarcode($code, $type, $x='', $y='', $w='', $h='', $xres='', $style='', $align='') {
		if ($this->empty_string(trim($code))) {
			return;
		}
		//include_once(dirname(__FILE__).'/barcodes.php');
		
		include_once('Classes/tcpdf/barcodes.php');
				
		// save current graphic settings
		$gvars = $this->getGraphicVars();
		// create new barcode object
		$barcodeobj = new TCPDFBarcode($code, $type);
		$arrcode = $barcodeobj->getBarcodeArray();
		if ($arrcode === false) {
			$this->Error('Error in 1D barcode string');
		}
		// set default values
		if (!isset($style['position'])) {
			$style['position'] = '';
		} elseif ($style['position'] == 'S') {
			// keep this for backward compatibility
			$style['position'] = '';
			$style['stretch'] = true;
		}
		if (!isset($style['fitwidth'])) {
			if (!isset($style['stretch'])) {
				$style['fitwidth'] = true;
			} else {
				$style['fitwidth'] = false;
			}
		}
		if ($style['fitwidth']) {
			// disable stretch
			$style['stretch'] = false;
		}
		if (!isset($style['stretch'])) {
			if (($w === '') OR ($w <= 0)) {
				$style['stretch'] = false;
			} else {
				$style['stretch'] = true;
			}
		}
		if (!isset($style['fgcolor'])) {
			$style['fgcolor'] = array(0,0,0); // default black
		}
		if (!isset($style['bgcolor'])) {
			$style['bgcolor'] = false; // default transparent
		}
		if (!isset($style['border'])) {
			$style['border'] = false;
		}
		$fontsize = 0;
		if (!isset($style['text'])) {
			$style['text'] = false;
		}
		if ($style['text'] AND isset($style['font'])) {
			if (isset($style['fontsize'])) {
				$fontsize = $style['fontsize'];
			}
			$this->SetFont($style['font'], '', $fontsize);
		}
		if (!isset($style['stretchtext'])) {
			$style['stretchtext'] = 4;
		}
		if ($x === '') {
			$x = $this->x;
		}
		if ($y === '') {
			$y = $this->y;
		}
		// check page for no-write regions and adapt page margins if necessary
		list($x, $y) = $this->checkPageRegions($h, $x, $y);
		if (($w === '') OR ($w <= 0)) {
			if ($this->rtl) {
				$w = $x - $this->lMargin;
			} else {
				$w = $this->w - $this->rMargin - $x;
			}
		}
		// padding
		if (!isset($style['padding'])) {
			$padding = 0;
		} elseif ($style['padding'] === 'auto') {
			$padding = 10 * ($w / ($arrcode['maxw'] + 20));
		} else {
			$padding = floatval($style['padding']);
		}
		// horizontal padding
		if (!isset($style['hpadding'])) {
			$hpadding = $padding;
		} elseif ($style['hpadding'] === 'auto') {
			$hpadding = 10 * ($w / ($arrcode['maxw'] + 20));
		} else {
			$hpadding = floatval($style['hpadding']);
		}
		// vertical padding
		if (!isset($style['vpadding'])) {
			$vpadding = $padding;
		} elseif ($style['vpadding'] === 'auto') {
			$vpadding = ($hpadding / 2);
		} else {
			$vpadding = floatval($style['vpadding']);
		}
		// calculate xres (single bar width)
		$max_xres = ($w - (2 * $hpadding)) / $arrcode['maxw'];
		if ($style['stretch']) {
			$xres = $max_xres;
		} else {
			if ($this->empty_string($xres)) {
				$xres = (0.141 * $this->k); // default bar width = 0.4 mm
			}
			if ($xres > $max_xres) {
				// correct xres to fit on $w
				$xres = $max_xres;
			}
			if ((isset($style['padding']) AND ($style['padding'] === 'auto'))
				OR (isset($style['hpadding']) AND ($style['hpadding'] === 'auto'))) {
				$hpadding = 10 * $xres;
				if (isset($style['vpadding']) AND ($style['vpadding'] === 'auto')) {
					$vpadding = ($hpadding / 2);
				}
			}
		}
		if ($style['fitwidth']) {
			$wold = $w;
			$w = (($arrcode['maxw'] * $xres) + (2 * $hpadding));
			if (isset($style['cellfitalign'])) {
				switch ($style['cellfitalign']) {
					case 'L': {
						if ($this->rtl) {
							$x -= ($wold - $w);
						}
						break;
					}
					case 'R': {
						if (!$this->rtl) {
							$x += ($wold - $w);
						}
						break;
					}
					case 'C': {
						if ($this->rtl) {
							$x -= (($wold - $w) / 2);
						} else {
							$x += (($wold - $w) / 2);
						}
						break;
					}
					default : {
						break;
					}
				}
			}
		}
		$text_height = ($this->cell_height_ratio * $fontsize / $this->k);
		// height
		if (($h === '') OR ($h <= 0)) {
			// set default height
			$h = (($arrcode['maxw'] * $xres) / 3) + (2 * $vpadding) + $text_height;
		}
		$barh = $h - $text_height - (2 * $vpadding);
		if ($barh <=0) {
			// try to reduce font or padding to fit barcode on available height
			if ($text_height > $h) {
				$fontsize = (($h * $this->k) / (4 * $this->cell_height_ratio));
				$text_height = ($this->cell_height_ratio * $fontsize / $this->k);
				$this->SetFont($style['font'], '', $fontsize);
			}
			if ($vpadding > 0) {
				$vpadding = (($h - $text_height) / 4);
			}
			$barh = $h - $text_height - (2 * $vpadding);
		}
		// fit the barcode on available space
		list($w, $h, $x, $y) = $this->fitBlock($w, $h, $x, $y, false);
		// set alignment
		$this->img_rb_y = $y + $h;
		// set alignment
		if ($this->rtl) {
			if ($style['position'] == 'L') {
				$xpos = $this->lMargin;
			} elseif ($style['position'] == 'C') {
				$xpos = ($this->w + $this->lMargin - $this->rMargin - $w) / 2;
			} elseif ($style['position'] == 'R') {
				$xpos = $this->w - $this->rMargin - $w;
			} else {
				$xpos = $x - $w;
			}
			$this->img_rb_x = $xpos;
		} else {
			if ($style['position'] == 'L') {
				$xpos = $this->lMargin;
			} elseif ($style['position'] == 'C') {
				$xpos = ($this->w + $this->lMargin - $this->rMargin - $w) / 2;
			} elseif ($style['position'] == 'R') {
				$xpos = $this->w - $this->rMargin - $w;
			} else {
				$xpos = $x;
			}
			$this->img_rb_x = $xpos + $w;
		}
		$xpos_rect = $xpos;
		if (!isset($style['align'])) {
			$style['align'] = 'C';
		}
		switch ($style['align']) {
			case 'L': {
				$xpos = $xpos_rect + $hpadding;
				break;
			}
			case 'R': {
				$xpos = $xpos_rect + ($w - ($arrcode['maxw'] * $xres)) - $hpadding;
				break;
			}
			case 'C':
			default : {
				$xpos = $xpos_rect + (($w - ($arrcode['maxw'] * $xres)) / 2);
				break;
			}
		}
		$xpos_text = $xpos;
		// barcode is always printed in LTR direction
		$tempRTL = $this->rtl;
		$this->rtl = false;
		// print background color
		if ($style['bgcolor']) {
			$this->Rect($xpos_rect, $y, $w, $h, $style['border'] ? 'DF' : 'F', '', $style['bgcolor']);
		} elseif ($style['border']) {
			$this->Rect($xpos_rect, $y, $w, $h, 'D');
		}
		// set foreground color
		$this->SetDrawColorArray($style['fgcolor']);
		$this->SetTextColorArray($style['fgcolor']);
		// print bars
		foreach ($arrcode['bcode'] as $k => $v) {
			$bw = ($v['w'] * $xres);
			if ($v['t']) {
				// draw a vertical bar
				$ypos = $y + $vpadding + ($v['p'] * $barh / $arrcode['maxh']);
				$this->Rect($xpos, $ypos, $bw, ($v['h'] * $barh / $arrcode['maxh']), 'F', array(), $style['fgcolor']);
			}
			$xpos += $bw;
		}
		// print text
		if ($style['text']) {
			if (isset($style['label']) AND !$this->empty_string($style['label'])) {
				$label = $style['label'];
			} else {
				$label = $code;
			}
			$txtwidth = ($arrcode['maxw'] * $xres);
			if ($this->GetStringWidth($label) > $txtwidth) {
				$style['stretchtext'] = 2;
			}
			// print text
			$this->x = $xpos_text;
			$this->y = $y + $vpadding + $barh + 1.5; // ระยะห่างจาก ด้านล่างของบาร์โค้ด
			$cellpadding = $this->cell_padding;
			$this->SetCellPadding(0);
			$this->Cell($txtwidth, '', $label, 0, 0, 'C', 0, '', $style['stretchtext'], false, 'T', 'T');
			$this->cell_padding = $cellpadding;
		}
		// restore original direction
		$this->rtl = $tempRTL;
		// restore previous settings
		$this->setGraphicVars($gvars);
		// set pointer to align the next text/objects
		switch($align) {
			case 'T':{
				$this->y = $y;
				$this->x = $this->img_rb_x;
				break;
			}
			case 'M':{
				$this->y = $y + round($h / 2);
				$this->x = $this->img_rb_x;
				break;
			}
			case 'B':{
				$this->y = $this->img_rb_y;
				$this->x = $this->img_rb_x;
				break;
			}
			case 'N':{
				$this->SetY($this->img_rb_y);
				break;
			}
			default:{
				break;
			}
		}
		$this->endlinex = $this->img_rb_x;
	}
		
		function SetMargins($left,$top,$right=-1){
			//Set left, top and right margins
			$this->lMargin=$left;
			$this->tMargin=$top;
			if($right==-1) $right=$left;
			$this->rMargin=$right;
		}

		function SetLeftMargin($margin){
			//Set left margin
			$this->lMargin=$margin;
			if($this->page>0 && $this->x<$margin)	$this->x=$margin;
		}

		function SetTopMargin($margin){
			//Set top margin
			$this->tMargin=$margin;
		}

		function SetRightMargin($margin) {
			//Set right margin
			$this->rMargin=$margin;
		}

		function SetAutoPageBreak($auto,$margin=0) {
			//Set auto page break mode and triggering margin
			$this->AutoPageBreak=$auto;
			$this->bMargin=$margin;
			$this->PageBreakTrigger=$this->h-$margin;
		}

		function SetDisplayMode($zoom,$layout='continuous') {
			//Set display mode in viewer
			if($zoom=='fullpage' || $zoom=='fullwidth' || $zoom=='real' || $zoom=='default' || !is_string($zoom)) $this->ZoomMode=$zoom;
			else 	$this->Error('Incorrect zoom display mode: '.$zoom);
			if($layout=='single' || $layout=='continuous' || $layout=='two' || $layout=='default') $this->LayoutMode=$layout;
			else $this->Error('Incorrect layout display mode: '.$layout);
		}

		function SetCompression($compress)
		{
			//Set page compression
			if(function_exists('gzcompress'))	$this->compress=$compress;
			else $this->compress=false;
		}

		function SetTitle($title) {
			//Title of document
			$this->title=$title;
		}

		function SetSubject($subject) {
			//Subject of document
			$this->subject=$subject;
		}

		function SetAuthor($author){
			//Author of document
			$this->author=$author;
		}

		function SetKeywords($keywords){
			//Keywords of document
			$this->keywords=$keywords;
		}

		function SetCreator($creator){
			//Creator of document
			$this->creator=$creator;
		}

		function AliasNbPages($alias='{nb}') {
	//Define an alias for total number of pages
			$this->AliasNbPages=$alias;
		}

		function Error($msg){
			//Fatal error
			die('<B>ThaiPDF error: </B>'.$msg);
		}

		function Open(){
			//Begin document
			$this->state=1;
		}

		function Close(){
			//Terminate document
			if($this->state==3) return;
			if($this->page==0) $this->AddPage();
			//Page footer
			$this->InFooter=true;
			$this->Footer();
			$this->InFooter=false;
			//Close page
			$this->_endpage();
			//Close document
			$this->_enddoc();
			parent::Close();
			foreach($this->tmpFiles as $tmp) @unlink($tmp);
		}

		function AddPage($orientation='') {
			//Start a new page
			if($this->state==0) $this->Open();
			$family=$this->FontFamily;
			$style=$this->FontStyle.($this->underline ? 'U' : '');
			$size=$this->FontSizePt;
			$lw=$this->LineWidth;
			$dc=$this->DrawColor;
			$fc=$this->FillColor;
			$tc=$this->TextColor;
			$cf=$this->ColorFlag;
			if($this->page>0){
				//Page footer
				$this->InFooter=true;
				$this->Footer();
				$this->InFooter=false;
				//Close page
				$this->_endpage();
			}
			//Start new page
			$this->_beginpage($orientation);
			//Set line cap style to square
			$this->_out('2 J');
			//Set line width
			$this->LineWidth=$lw;
			$this->_out(sprintf('%.2f w',$lw*$this->k));
			//Set font
			if($family) $this->SetFont($family,$style,$size);
			//Set colors
			$this->DrawColor=$dc;
			if($dc!='0 G') $this->_out($dc);
			$this->FillColor=$fc;
			if($fc!='0 g') $this->_out($fc);
			$this->TextColor=$tc;
			$this->ColorFlag=$cf;
			//Page header
			$this->Header();
			//Restore line width
			if($this->LineWidth!=$lw){
				$this->LineWidth=$lw;
				$this->_out(sprintf('%.2f w',$lw*$this->k));
			}
			//Restore font
			if($family) $this->SetFont($family,$style,$size);
			//Restore colors
			if($this->DrawColor!=$dc){
				$this->DrawColor=$dc;
				$this->_out($dc);
			}
			if($this->FillColor!=$fc){
				$this->FillColor=$fc;
				$this->_out($fc);
			}
			$this->TextColor=$tc;
			$this->ColorFlag=$cf;
		}
		
		function SetHeader($SetTil , $SetShwP, $SetAlgn, $SetAdd){
			$this->HTitle = $SetTil;
			$this->HShowPNo = $SetShwP;
			$this->HAlign = $SetAlgn;
			$this->HAdd = $SetAdd;			
		}

		function SetFooter($SetTil , $SetShwP, $SetAlgn, $SetAdd){
			$this->VTitle = $SetTil;
			$this->VShowPNo = $SetShwP;
			$this->VAlign = $SetAlgn;
			$this->VAdd = $SetAdd;			
		}

		function Header() {
			if($this->HAdd == 1){
    			//Select Arial bold 15
    			$Show = $this->HTitle;
				if($this->HShowPNo == 1)$Show = $Show . '' . $this->PageNo();
				$this->SetFont('CordiaNew','B',12);
    			//Framed title
    			$this->Cell(0,12,$Show,0,0,$this->HAlign);
    			//Line break
   			 	$this->Ln(10);
			 }
		}

		function Footer()	{
			if($this->VAdd == 1){
			 	$Show = $this->VTitle;
			 	if($this->VShowPNo == 1)$Show = $Show . '' . $this->PageNo();
				//Go to 1.5 cm from bottom
    			$this->SetY(-15);
    			//Select Arial italic 8
    			$this->SetFont('CordiaNew','I',12);
				//Print current and total page numbers
   		 		$this->Cell(0,12,$Show,0,0,$this->VAlign);
			}
		}

		function PageNo()	{
			//Get current page number
			return $this->page;
		}

		function SetDrawColor($r,$g=-1,$b=-1) {
			//Set color for all stroking operations
			if(($r==0 && $g==0 && $b==0) || $g==-1) $this->DrawColor=sprintf('%.3f G',$r/255);
			else $this->DrawColor=sprintf('%.3f %.3f %.3f RG',$r/255,$g/255,$b/255);
			if($this->page>0) $this->_out($this->DrawColor);
		}

		function SetFillColor($r,$g=-1,$b=-1) {
			//Set color for all filling operations
			if(($r==0 && $g==0 && $b==0) || $g==-1) $this->FillColor=sprintf('%.3f g',$r/255);
			else $this->FillColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
			$this->ColorFlag=($this->FillColor!=$this->TextColor);
			if($this->page>0)	$this->_out($this->FillColor);
		}

		function SetTextColor($r,$g=-1,$b=-1) {
			//Set color for text
			if(($r==0 && $g==0 && $b==0) || $g==-1) $this->TextColor=sprintf('%.3f g',$r/255);
			else $this->TextColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
			$this->ColorFlag=($this->FillColor!=$this->TextColor);
		}

		function GetStringWidth($s) {
			//Get width of a string in the current font
			$s=(string)$s;
			$cw=&$this->CurrentFont['cw'];
			$w=0;
			$l=strlen($s);
			for($i=0;$i<$l;$i++) 	$w+=$cw[$s{$i}];
			return $w*$this->FontSize/1000;
		}

		function SetLineWidth($width) {
			//Set line width
			$this->LineWidth=$width;
			if($this->page>0) $this->_out(sprintf('%.2f w',$width*$this->k));
		}

		function Line($x1,$y1,$x2,$y2) {
			//Draw a line
			$this->_out(sprintf('%.2f %.2f m %.2f %.2f l S',$x1*$this->k,($this->h-$y1)*$this->k,$x2*$this->k,($this->h-$y2)*$this->k));
		}

		function Rect($x,$y,$w,$h,$style='') {
			//Draw a rectangle
			if($style=='F') $op='f';
			elseif($style=='FD' || $style=='DF') $op='B';
			else	$op='S';
			$this->_out(sprintf('%.2f %.2f %.2f %.2f re %s',$x*$this->k,($this->h-$y)*$this->k,$w*$this->k,-$h*$this->k,$op));
		}

		function AddFont($family,$style='',$file=''){
			//Add a TrueType or Type1 font
			$family=strtolower($family);
			if($file=='') $file=str_replace(' ','',$family).strtolower($style).'.php';
			if($family=='arial') $family='helvetica';
			$style=strtoupper($style);
			if($style=='IB')	$style='BI';
			$fontkey=$family.$style;
			if(isset($this->fonts[$fontkey])) $this->Error('Font already added: '.$family.' '.$style);
			include($this->_getfontpath().$file);
			if(!isset($name)) $this->Error('Could not include font definition file');
			$i=count($this->fonts)+1;
			$this->fonts[$fontkey]=array('i'=>$i,'type'=>$type,'name'=>$name,'desc'=>$desc,'up'=>$up,'ut'=>$ut,'cw'=>$cw,'enc'=>$enc,'file'=>$file);
			if($diff)	{
				//Search existing encodings
				$d=0;
				$nb=count($this->diffs);
				for($i=1;$i<=$nb;$i++) {
					if($this->diffs[$i]==$diff) {
						$d=$i;
						break;
					}
				}
				if($d==0)	{
					$d=$nb+1;
					$this->diffs[$d]=$diff;
				}
				$this->fonts[$fontkey]['diff']=$d;
			}
			if($file) {
				if($type=='TrueType') $this->FontFiles[$file]=array('length1'=>$originalsize);
				else $this->FontFiles[$file]=array('length1'=>$size1,'length2'=>$size2);
			}
		}

		function SetFont($family,$style='',$size=0) {
			//Select a font; size given in points
			global $fpdf_charwidths;
			$family=strtolower($family);
			if($family=='') $family=$this->FontFamily;
			if($family=='arial') $family='helvetica';
			elseif($family=='symbol' || $family=='zapfdingbats') $style='';
			$style=strtoupper($style);
			if(strpos($style,'U')!==false) {
				$this->underline=true;
				$style=str_replace('U','',$style);
			}
			else 	$this->underline=false;
			if($style=='IB') $style='BI';
			if($size==0) $size=$this->FontSizePt;
			//Test if font is already selected
			if($this->FontFamily==$family && $this->FontStyle==$style && $this->FontSizePt==$size) return;
			//Test if used for the first time
			$fontkey=$family.$style;
			if(!isset($this->fonts[$fontkey])) {
				//Check if one of the standard fonts
				if(isset($this->CoreFonts[$fontkey])) {
					if(!isset($fpdf_charwidths[$fontkey])) {
						//Load metric file
						$file=$family;
						if($family=='times' || $family=='helvetica') $file.=strtolower($style);
						include($this->_getfontpath().$file.'.php');
						if(!isset($fpdf_charwidths[$fontkey])) $this->Error('Could not include font metric file');
					}
					$i=count($this->fonts)+1;
					$this->fonts[$fontkey]=array('i'=>$i,'type'=>'core','name'=>$this->CoreFonts[$fontkey],'up'=>-100,'ut'=>50,'cw'=>$fpdf_charwidths[$fontkey]);
				}
				else $this->Error('Undefined font: '.$family.' '.$style);
			}
			//Select it
			$this->FontFamily=$family;
			$this->FontStyle=$style;
			$this->FontSizePt=$size;
			$this->FontSize=$size/$this->k;
			$this->CurrentFont=&$this->fonts[$fontkey];
			if($this->page>0) $this->_out(sprintf('BT /F%d %.2f Tf ET',$this->CurrentFont['i'],$this->FontSizePt));
		}

		function SetFontSize($size) {
			//Set font size in points
			if($this->FontSizePt==$size) return;
			$this->FontSizePt=$size;
			$this->FontSize=$size/$this->k;
			if($this->page>0) 	$this->_out(sprintf('BT /F%d %.2f Tf ET',$this->CurrentFont['i'],$this->FontSizePt));
		}

		function AddLink() {
			//Create a new internal link
			$n=count($this->links)+1;
			$this->links[$n]=array(0,0);
			return $n;
		}

		function SetLink($link,$y=0,$page=-1) {
			//Set destination of internal link
			if($y==-1) $y=$this->y;
			if($page==-1) $page=$this->page;
			$this->links[$link]=array($page,$y);
		}

		function Link($x,$y,$w,$h,$link) {
			//Put a link on the page
			$this->PageLinks[$this->page][]=array($x*$this->k,$this->hPt-$y*$this->k,$w*$this->k,$h*$this->k,$link);
		}

		function Text($x,$y,$txt) {
			//Output a string
			$s=sprintf('BT %.2f %.2f Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
			if($this->underline && $txt!='') $s.=' '.$this->_dounderline($x,$y,$txt);
			if($this->ColorFlag) $s='q '.$this->TextColor.' '.$s.' Q';
			$this->_out($s);
		}

		function AcceptPageBreak() {
			//Accept automatic page break or not
			return $this->AutoPageBreak;
		}

		function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='') {
			//Output a cell
			$k=$this->k;
			if($this->y+$h>$this->PageBreakTrigger && !$this->InFooter && $this->AcceptPageBreak()) {
				//Automatic page break
				$x=$this->x;
				$ws=$this->ws;
				if($ws>0) {
					$this->ws=0;
					$this->_out('0 Tw');
				}
				$this->AddPage($this->CurOrientation);
				$this->x=$x;
				if($ws>0) {
					$this->ws=$ws;
					$this->_out(sprintf('%.3f Tw',$ws*$k));
				}
			}
			if($w==0) $w=$this->w-$this->rMargin-$this->x;
			$s='';
			if($fill==1 || $border==1) 	{
				if($fill==1) $op=($border==1) ? 'B' : 'f';
				else $op='S';
				$s=sprintf('%.2f %.2f %.2f %.2f re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
			}
			if(is_string($border)) {
				$x=$this->x;
				$y=$this->y;
				if(strpos($border,'L')!==false) 	$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
				if(strpos($border,'T')!==false) 	$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
				if(strpos($border,'R')!==false) $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
				if(strpos($border,'B')!==false) $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
			}
			if($txt!=='') {
				if($align=='R') $dx=$w-$this->cMargin-$this->GetStringWidth($txt);
				elseif($align=='C') $dx=($w-$this->GetStringWidth($txt))/2;
				else $dx=$this->cMargin;
				if($this->ColorFlag) $s.='q '.$this->TextColor.' ';
				$txt2=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
				$s.=sprintf('BT %.2f %.2f Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt2);
				if($this->underline) $s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
				if($this->ColorFlag) $s.=' Q';
				if($link) $this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);
			}
			if($s) $this->_out($s);
			$this->lasth=$h;
			if($ln>0) {
				//Go to next line
				$this->y+=$h;
				if($ln==1) $this->x=$this->lMargin;
			}
			else $this->x+=$w;
		}

		function MultiCell($w,$h,$txt,$border=0,$align='J',$fill=0) {
			//Output text with automatic or explicit line breaks
			$cw=&$this->CurrentFont['cw'];
			if($w==0) $w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 && $s[$nb-1]=="\n") $nb--;
			$b=0;
			if($border) {
				if($border==1) {
					$border='LTRB';
					$b='LRT';
					$b2='LR';
				}
				else {
					$b2='';
					if(strpos($border,'L')!==false) 	$b2.='L';
					if(strpos($border,'R')!==false) $b2.='R';
					$b=(strpos($border,'T')!==false) ? $b2.'T' : $b2;
				}
			}
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$ns=0;
			$nl=1;
			while($i<$nb) {
				//Get next character
				$c=$s{$i};
				if($c=="\n") {
					//Explicit line break
					if($this->ws>0) {
						$this->ws=0;
						$this->_out('0 Tw');
					}
					$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$ns=0;
					$nl++;
					if($border && $nl==2) $b=$b2;
					continue;
				}
				if($c==' ')	{
					$sep=$i;
					$ls=$l;
					$ns++;
				}
				$l+=$cw[$c];
				if($l>$wmax) {
					//Automatic line break
					if($sep==-1) {
						if($i==$j) $i++;
						if($this->ws>0) {
							$this->ws=0;
							$this->_out('0 Tw');
						}
						$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
					}
					else {
						if($align=='J') {
							$this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
							$this->_out(sprintf('%.3f Tw',$this->ws*$this->k));
						}
						$this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
						$i=$sep+1;
					}
					$sep=-1;
					$j=$i;
					$l=0;
					$ns=0;
					$nl++;
					if($border && $nl==2) $b=$b2;
				}
				else $i++;
			}
			//Last chunk
			if($this->ws>0) {
				$this->ws=0;
				$this->_out('0 Tw');
			}
			if($border && strpos($border,'B')!==false) $b.='B';
			$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
			$this->x=$this->lMargin;
		}

		function Write($h,$txt,$link='') {
			//Output text in flowing mode
			$cw=&$this->CurrentFont['cw'];
			$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb) {
				//Get next character
				$c=$s{$i};
				if($c=="\n") {
					//Explicit line break
					$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					if($nl==1) {
						$this->x=$this->lMargin;
						$w=$this->w-$this->rMargin-$this->x;
						$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
					}
					$nl++;
					continue;
				}
				if($c==' ') $sep=$i;
				$l+=$cw[$c];
				if($l>$wmax) {
					//Automatic line break
					if($sep==-1) {
						if($this->x>$this->lMargin) {
							//Move to next line
							$this->x=$this->lMargin;
							$this->y+=$h;
							$w=$this->w-$this->rMargin-$this->x;
							$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
							$i++;
							$nl++;
							continue;
						}
						if($i==$j) $i++;
						$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
					}
					else {
						$this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
						$i=$sep+1;
					}
					$sep=-1;
					$j=$i;
					$l=0;
					if($nl==1) {
						$this->x=$this->lMargin;
						$w=$this->w-$this->rMargin-$this->x;
						$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
					}
					$nl++;
				}
				else 	$i++;
			}
			//Last chunk
			if($i!=$j) $this->Cell($l/1000*$this->FontSize,$h,substr($s,$j),0,0,'',0,$link);
		}

		/*function Image($file,$x,$y,$w=0,$h=0,$type='',$link='') {
			//Put an image on the page
			if(!isset($this->images[$file])) {
				//First use of image, get info
				if($type=='') {
					$pos=strrpos($file,'.');
					if(!$pos) $this->Error('Image file has no extension and no type was specified: '.$file);
					$type=substr($file,$pos+1);
				}
				$type=strtolower($type);
				$mqr=get_magic_quotes_runtime();
				set_magic_quotes_runtime(0);
				if($type=='jpg' || $type=='jpeg') $info=$this->_parsejpg($file);
				elseif($type=='png') $info=$this->_parsepng($file);
				else {
					//Allow for additional formats
					$mtd='_parse'.$type;
					if(!method_exists($this,$mtd)) 	$this->Error('Unsupported image type: '.$type);
					$info=$this->$mtd($file);
				}
				set_magic_quotes_runtime($mqr);
				$info['i']=count($this->images)+1;
				$this->images[$file]=$info;
			}
			else $info=$this->images[$file];
			//Automatic width and height calculation if needed
			if($w==0 && $h==0) {
		   		//Put image at 72 dpi
				$w=$info['w']/$this->k;
				$h=$info['h']/$this->k;
			}
			if($w==0) $w=$h*$info['w']/$info['h'];
			if($h==0) $h=$w*$info['h']/$info['w'];
			$this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
			if($link) $this->Link($x,$y,$w,$h,$link);
		}*/
		
		function Image($file,$x,$y,$w=0,$h=0,$type='',$link='', $isMask=false, $maskImg=0)
		{
			//Put an image on the page
			if(!isset($this->images[$file]))
			{
				//First use of image, get info
				if($type=='')
				{
					$pos=strrpos($file,'.');
					if(!$pos)
						$this->Error('Image file has no extension and no type was specified: '.$file);
					$type=substr($file,$pos+1);
				}
				$type=strtolower($type);
				$mqr=get_magic_quotes_runtime();
				set_magic_quotes_runtime(0);
				if($type=='jpg' || $type=='jpeg')
					$info=$this->_parsejpg($file);
				elseif($type=='png'){
					$info=$this->_parsepng($file);
					if ($info=='alpha') return $this->ImagePngWithAlpha($file,$x,$y,$w,$h,$link);
				}
				else
				{
					//Allow for additional formats
					$mtd='_parse'.$type;
					if(!method_exists($this,$mtd))
						$this->Error('Unsupported image type: '.$type);
					$info=$this->$mtd($file);
				}
				set_magic_quotes_runtime($mqr);
				
				if ($isMask){
			  $info['cs']="DeviceGray"; // try to force grayscale (instead of indexed)
			}
				$info['i']=count($this->images)+1;
				if ($maskImg>0) $info['masked'] = $maskImg;###
				$this->images[$file]=$info;
			}
			else
				$info=$this->images[$file];
			//Automatic width and height calculation if needed
			if($w==0 && $h==0)
			{
				//Put image at 72 dpi
				$w=$info['w']/$this->k;
				$h=$info['h']/$this->k;
			}
			if($w==0)
				$w=$h*$info['w']/$info['h'];
			if($h==0)
				$h=$w*$info['h']/$info['w'];
			
			// embed hidden, ouside the canvas
			if ((float)FPDF_VERSION>=1.7){
				if ($isMask) $x = ($this->CurOrientation=='P'?$this->CurPageSize[0]:$this->CurPageSize[1]) + 10;
			}else{
				if ($isMask) $x = ($this->CurOrientation=='P'?$this->CurPageFormat[0]:$this->CurPageFormat[1]) + 10;
			}
			
			$this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
			if($link)
				$this->Link($x,$y,$w,$h,$link);
				
			return $info['i'];
		}
		
		function ImagePngWithAlpha($file,$x,$y,$w=0,$h=0,$link='')
		{
			$tmp_alpha = tempnam('.', 'mska');
			$this->tmpFiles[] = $tmp_alpha;
			$tmp_plain = tempnam('.', 'mskp');
			$this->tmpFiles[] = $tmp_plain;
			
			list($wpx, $hpx) = getimagesize($file);
			$img = imagecreatefrompng($file);
			$alpha_img = imagecreate( $wpx, $hpx );
			
			// generate gray scale pallete
			for($c=0;$c<256;$c++) ImageColorAllocate($alpha_img, $c, $c, $c);
			
			// extract alpha channel
			$xpx=0;
			while ($xpx<$wpx){
				$ypx = 0;
				while ($ypx<$hpx){
					$color_index = imagecolorat($img, $xpx, $ypx);
					$alpha = 255-($color_index>>24)*255/127; // GD alpha component: 7 bit only, 0..127!
					imagesetpixel($alpha_img, $xpx, $ypx, $alpha);
				++$ypx;
				}
				++$xpx;
			}

			imagepng($alpha_img, $tmp_alpha);
			imagedestroy($alpha_img);
			
			// extract image without alpha channel
			$plain_img = imagecreatetruecolor ( $wpx, $hpx );
			imagecopy ($plain_img, $img, 0, 0, 0, 0, $wpx, $hpx );
			imagepng($plain_img, $tmp_plain);
			imagedestroy($plain_img);
			
			//first embed mask image (w, h, x, will be ignored)
			$maskImg = $this->Image($tmp_alpha, 0,0,0,0, 'PNG', '', true); 
			
			//embed image, masked with previously embedded mask
			$this->Image($tmp_plain,$x,$y,$w,$h,'PNG',$link, false, $maskImg);
		}

		function Ln($h='') {
			//Line feed; default value is last cell height
			$this->x=$this->lMargin;
			if(is_string($h)) $this->y+=$this->lasth;
	   		else $this->y+=$h;
		}

		function GetX() {
			//Get x position
			return $this->x;
		}

		function SetX($x) {
			//Set x position
			if($x>=0) $this->x=$x;
			else $this->x=$this->w+$x;
		}

		function GetY() {
			//Get y position
			return $this->y;
		}

		function SetY($y) {
			//Set y position and reset x
			$this->x=$this->lMargin;
			if($y>=0) $this->y=$y;
			else $this->y=$this->h+$y;
		}

		function SetXY($x,$y) {
			//Set x and y positions
			$this->SetY($y);
			$this->SetX($x);
		}

		function Output($name='',$dest='') {
			//Output PDF to some destination
			//Finish document if necessary
			if($this->state<3) $this->Close();
			//Normalize parameters
			if(is_bool($dest)) $dest=$dest ? 'D' : 'F';
			$dest=strtoupper($dest);
			if($dest=='') {
				if($name=='') {
					$name='doc.pdf';
					$dest='I';
				}
				else $dest='F';
			}
			switch($dest) {
				case 'I':
					//Send to standard output
					if(ob_get_contents()) $this->Error('Some data has already been output, can\'t send PDF file');
					if(php_sapi_name()!='cli') {
						//We send to a browser
						header('Content-Type: application/pdf');
						if(headers_sent()) $this->Error('Some data has already been output to browser, can\'t send PDF file');
						header('Content-Length: '.strlen($this->buffer));
						header('Content-disposition: inline; filename="'.$name.'"');
					}
					echo $this->buffer;
					break;
				case 'D':
					//Download file
					if(ob_get_contents()) $this->Error('Some data has already been output, can\'t send PDF file');
					if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')) header('Content-Type: application/force-download');
					else header('Content-Type: application/octet-stream');
					if(headers_sent()) $this->Error('Some data has already been output to browser, can\'t send PDF file');
					header('Content-Length: '.strlen($this->buffer));
					header('Content-disposition: attachment; filename="'.$name.'"');
					echo $this->buffer;
					break;
				case 'F':
					//Save to local file
					$f=fopen($name,'wb');
					if(!$f) $this->Error('Unable to create output file: '.$name);
					fwrite($f,$this->buffer,strlen($this->buffer));
					fclose($f);
					break;
				case 'S':
					//Return as a string
					return $this->buffer;
				default:
					$this->Error('Incorrect output destination: '.$dest);
			}
			return '';
		}

/*******************************************************************************
*                              Protected methods                               *
*******************************************************************************/
		function _dochecks() {
			//Check for locale-related bug
			if(1.1==1) 	$this->Error('Don\'t alter the locale before including class file');
			//Check for decimal separator
			if(sprintf('%.1f',1.0)!='1.0') setlocale(LC_NUMERIC,'C');
		}

		function _getfontpath() {
			if(!defined('ThaiPDF_FontPath') && is_dir(dirname(__FILE__).'/font')) define('ThaiPDF_FontPath',dirname(__FILE__).'/font/');
			return defined('ThaiPDF_FontPath') ? ThaiPDF_FontPath : '';
		}

		function _putpages() {
			$nb=$this->page;
			if(!empty($this->AliasNbPages)) {
				//Replace number of pages
				for($n=1;$n<=$nb;$n++) $this->pages[$n]=str_replace($this->AliasNbPages,$nb,$this->pages[$n]);
			}
			if($this->DefOrientation=='P') {
				$wPt=$this->fwPt;
				$hPt=$this->fhPt;
			}
			else {
				$wPt=$this->fhPt;
				$hPt=$this->fwPt;
			}
			$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
			for($n=1;$n<=$nb;$n++) {
				//Page
				$this->_newobj();
				$this->_out('<</Type /Page');
				$this->_out('/Parent 1 0 R');
				if(isset($this->OrientationChanges[$n])) $this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$hPt,$wPt));
				$this->_out('/Resources 2 0 R');
				if(isset($this->PageLinks[$n])) {
					//Links
					$annots='/Annots [';
					foreach($this->PageLinks[$n] as $pl) {
						$rect=sprintf('%.2f %.2f %.2f %.2f',$pl[0],$pl[1],$pl[0]+$pl[2],$pl[1]-$pl[3]);
						$annots.='<</Type /Annot /Subtype /Link /Rect ['.$rect.'] /Border [0 0 0] ';
						if(is_string($pl[4])) $annots.='/A <</S /URI /URI '.$this->_textstring($pl[4]).'>>>>';
						else {
							$l=$this->links[$pl[4]];
							$h=isset($this->OrientationChanges[$l[0]]) ? $wPt : $hPt;
							$annots.=sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]>>',1+2*$l[0],$h-$l[1]*$this->k);
						}
					}
					$this->_out($annots.']');
				}
				$this->_out('/Contents '.($this->n+1).' 0 R>>');
				$this->_out('endobj');
				//Page content
				$p=($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
				$this->_newobj();
				$this->_out('<<'.$filter.'/Length '.strlen($p).'>>');
				$this->_putstream($p);
				$this->_out('endobj');
			}
			//Pages root
			$this->offsets[1]=strlen($this->buffer);
			$this->_out('1 0 obj');
			$this->_out('<</Type /Pages');
			$kids='/Kids [';
			for($i=0;$i<$nb;$i++) $kids.=(3+2*$i).' 0 R ';
			$this->_out($kids.']');
			$this->_out('/Count '.$nb);
			$this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$wPt,$hPt));
			$this->_out('>>');
			$this->_out('endobj');
		}

		function _putfonts() {
			$nf=$this->n;
			foreach($this->diffs as $diff) {
				//Encodings
				$this->_newobj();
				$this->_out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences ['.$diff.']>>');
				$this->_out('endobj');
			}
			$mqr=get_magic_quotes_runtime();
			set_magic_quotes_runtime(0);
			foreach($this->FontFiles as $file=>$info) {
				//Font file embedding
				$this->_newobj();
				$this->FontFiles[$file]['n']=$this->n;
				$font='';
				$f=fopen($this->_getfontpath().$file,'rb',1);
				if(!$f) $this->Error('Font file not found');
				while(!feof($f)) $font.=fread($f,8192);
				fclose($f);
				$compressed=(substr($file,-2)=='.z');
				if(!$compressed && isset($info['length2'])) {
					$header=(ord($font{0})==128);
					if($header) {
						//Strip first binary header
						$font=substr($font,6);
					}
					if($header && ord($font{$info['length1']})==128) {
						//Strip second binary header
						$font=substr($font,0,$info['length1']).substr($font,$info['length1']+6);
					}
				}
				$this->_out('<</Length '.strlen($font));
				if($compressed) $this->_out('/Filter /FlateDecode');
				$this->_out('/Length1 '.$info['length1']);
				if(isset($info['length2'])) $this->_out('/Length2 '.$info['length2'].' /Length3 0');
				$this->_out('>>');
				$this->_putstream($font);
				$this->_out('endobj');
			}
			set_magic_quotes_runtime($mqr);
			foreach($this->fonts as $k=>$font) {
				//Font objects
				$this->fonts[$k]['n']=$this->n+1;
				$type=$font['type'];
				$name=$font['name'];
				if($type=='core') {
					//Standard font
					$this->_newobj();
					$this->_out('<</Type /Font');
					$this->_out('/BaseFont /'.$name);
					$this->_out('/Subtype /Type1');
					if($name!='Symbol' && $name!='ZapfDingbats') $this->_out('/Encoding /WinAnsiEncoding');
					$this->_out('>>');
					$this->_out('endobj');
				}
				elseif($type=='Type1' || $type=='TrueType') {
					//Additional Type1 or TrueType font
					$this->_newobj();
					$this->_out('<</Type /Font');
					$this->_out('/BaseFont /'.$name);
					$this->_out('/Subtype /'.$type);
					$this->_out('/FirstChar 32 /LastChar 255');
					$this->_out('/Widths '.($this->n+1).' 0 R');
					$this->_out('/FontDescriptor '.($this->n+2).' 0 R');
					if($font['enc']) {
						if(isset($font['diff']))	$this->_out('/Encoding '.($nf+$font['diff']).' 0 R');
						else $this->_out('/Encoding /WinAnsiEncoding');
					}
					$this->_out('>>');
					$this->_out('endobj');
					//Widths
					$this->_newobj();
					$cw=&$font['cw'];
					$s='[';
					for($i=32;$i<=255;$i++) $s.=$cw[chr($i)].' ';
					$this->_out($s.']');
					$this->_out('endobj');
					//Descriptor
					$this->_newobj();
					$s='<</Type /FontDescriptor /FontName /'.$name;
					foreach($font['desc'] as $k=>$v) $s.=' /'.$k.' '.$v;
					$file=$font['file'];
					if($file) $s.=' /FontFile'.($type=='Type1' ? '' : '2').' '.$this->FontFiles[$file]['n'].' 0 R';
					$this->_out($s.'>>');
					$this->_out('endobj');
				}
				else {
					//Allow for additional types
					$mtd='_put'.strtolower($type);
					if(!method_exists($this,$mtd)) $this->Error('Unsupported font type: '.$type);
					$this->$mtd($font);
				}
			}
		}

		/*function _putimages() {
			$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
			reset($this->images);
			while(list($file,$info)=each($this->images)) {
				$this->_newobj();
				$this->images[$file]['n']=$this->n;
				$this->_out('<</Type /XObject');
				$this->_out('/Subtype /Image');
				$this->_out('/Width '.$info['w']);
				$this->_out('/Height '.$info['h']);
				if($info['cs']=='Indexed') $this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
				else {
					$this->_out('/ColorSpace /'.$info['cs']);
					if($info['cs']=='DeviceCMYK') $this->_out('/Decode [1 0 1 0 1 0 1 0]');
				}
				$this->_out('/BitsPerComponent '.$info['bpc']);
				if(isset($info['f'])) $this->_out('/Filter /'.$info['f']);
				if(isset($info['parms'])) $this->_out($info['parms']);
				if(isset($info['trns']) && is_array($info['trns'])) {
					$trns='';
					for($i=0;$i<count($info['trns']);$i++) $trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
					$this->_out('/Mask ['.$trns.']');
				}
				$this->_out('/Length '.strlen($info['data']).'>>');
				$this->_putstream($info['data']);
				unset($this->images[$file]['data']);
				$this->_out('endobj');
				//Palette
				if($info['cs']=='Indexed') {
					$this->_newobj();
					$pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
					$this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
					$this->_putstream($pal);
					$this->_out('endobj');
				}
			}
		}*/
		
		function _putimages()
		{
			$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
			reset($this->images);
			while(list($file,$info)=each($this->images))
			{
				$this->_newobj();
				$this->images[$file]['n']=$this->n;
				$this->_out('<</Type /XObject');
				$this->_out('/Subtype /Image');
				$this->_out('/Width '.$info['w']);
				$this->_out('/Height '.$info['h']);
				
				if (isset($info["masked"])) $this->_out('/SMask '.($this->n-1).' 0 R'); ###
				
				if($info['cs']=='Indexed')
					$this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
				else
				{
					$this->_out('/ColorSpace /'.$info['cs']);
					if($info['cs']=='DeviceCMYK')
						$this->_out('/Decode [1 0 1 0 1 0 1 0]');
				}
				$this->_out('/BitsPerComponent '.$info['bpc']);
				if(isset($info['f']))
					$this->_out('/Filter /'.$info['f']);
				if(isset($info['parms']))
					$this->_out($info['parms']);
				if(isset($info['trns']) && is_array($info['trns']))
				{
					$trns='';
					for($i=0;$i<count($info['trns']);$i++)
						$trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
					$this->_out('/Mask ['.$trns.']');
				}
				$this->_out('/Length '.strlen($info['data']).'>>');
				$this->_putstream($info['data']);
				unset($this->images[$file]['data']);
				$this->_out('endobj');
				//Palette
				if($info['cs']=='Indexed')
				{
					$this->_newobj();
					$pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
					$this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
					$this->_putstream($pal);
					$this->_out('endobj');
				}
			}
		}

		function _putxobjectdict() {
			foreach($this->images as $image) $this->_out('/I'.$image['i'].' '.$image['n'].' 0 R');
		}

		function _putresourcedict() {
			$this->_out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
			$this->_out('/Font <<');
			foreach($this->fonts as $font) $this->_out('/F'.$font['i'].' '.$font['n'].' 0 R');
			$this->_out('>>');
			$this->_out('/XObject <<');
			$this->_putxobjectdict();
			$this->_out('>>');
		}

		function _putresources() {
			$this->_putfonts();
			$this->_putimages();
			//Resource dictionary
			$this->offsets[2]=strlen($this->buffer);
			$this->_out('2 0 obj');
			$this->_out('<<');
			$this->_putresourcedict();
			$this->_out('>>');
			$this->_out('endobj');
		}

		function _putinfo() {
			$this->_out('/Producer '.$this->_textstring('Thai PDF '.ThaiPDFVersion));
			if(!empty($this->title)) $this->_out('/Title '.$this->_textstring($this->title));
			if(!empty($this->subject)) $this->_out('/Subject '.$this->_textstring($this->subject));
			if(!empty($this->author)) $this->_out('/Author '.$this->_textstring($this->author));
			if(!empty($this->keywords)) $this->_out('/Keywords '.$this->_textstring($this->keywords));
			if(!empty($this->creator)) $this->_out('/Creator '.$this->_textstring($this->creator));
			$this->_out('/CreationDate '.$this->_textstring('D:'.date('YmdHis')));
		}

		function _putcatalog() {
			$this->_out('/Type /Catalog');
			$this->_out('/Pages 1 0 R');
			if($this->ZoomMode=='fullpage') $this->_out('/OpenAction [3 0 R /Fit]');
			elseif($this->ZoomMode=='fullwidth') $this->_out('/OpenAction [3 0 R /FitH null]');
			elseif($this->ZoomMode=='real') $this->_out('/OpenAction [3 0 R /XYZ null null 1]');
			elseif(!is_string($this->ZoomMode)) $this->_out('/OpenAction [3 0 R /XYZ null null '.($this->ZoomMode/100).']');
			if($this->LayoutMode=='single') $this->_out('/PageLayout /SinglePage');
			elseif($this->LayoutMode=='continuous') $this->_out('/PageLayout /OneColumn');
			elseif($this->LayoutMode=='two') $this->_out('/PageLayout /TwoColumnLeft');
		}

		function _putheader() {
			$this->_out('%PDF-'.$this->PDFVersion);
		}

		function _puttrailer() {
			$this->_out('/Size '.($this->n+1));
			$this->_out('/Root '.$this->n.' 0 R');
			$this->_out('/Info '.($this->n-1).' 0 R');
		}

		function _enddoc() {
			$this->_putheader();
			$this->_putpages();
			$this->_putresources();
			//Info
			$this->_newobj();
			$this->_out('<<');
			$this->_putinfo();
			$this->_out('>>');
			$this->_out('endobj');
			//Catalog
			$this->_newobj();
			$this->_out('<<');
			$this->_putcatalog();
			$this->_out('>>');
			$this->_out('endobj');
			//Cross-ref
			$o=strlen($this->buffer);
			$this->_out('xref');
			$this->_out('0 '.($this->n+1));
			$this->_out('0000000000 65535 f ');
			for($i=1;$i<=$this->n;$i++) $this->_out(sprintf('%010d 00000 n ',$this->offsets[$i]));
			//Trailer
			$this->_out('trailer');
			$this->_out('<<');
			$this->_puttrailer();
			$this->_out('>>');
			$this->_out('startxref');
			$this->_out($o);
			$this->_out('%%EOF');
			$this->state=3;
		}

		function _beginpage($orientation) {
			$this->page++;
			$this->pages[$this->page]='';
			$this->state=2;
			$this->x=$this->lMargin;
			$this->y=$this->tMargin;
			$this->FontFamily='';
			//Page orientation
			if(!$orientation) $orientation=$this->DefOrientation;
			else {
				$orientation=strtoupper($orientation{0});
				if($orientation!=$this->DefOrientation) $this->OrientationChanges[$this->page]=true;
			}
			if($orientation!=$this->CurOrientation) {
				//Change orientation
				if($orientation=='P') {
					$this->wPt=$this->fwPt;
					$this->hPt=$this->fhPt;
					$this->w=$this->fw;
					$this->h=$this->fh;
				}
				else {
					$this->wPt=$this->fhPt;
					$this->hPt=$this->fwPt;
					$this->w=$this->fh;
					$this->h=$this->fw;
				}
				$this->PageBreakTrigger=$this->h-$this->bMargin;
				$this->CurOrientation=$orientation;
			}
		}

		function _endpage() {
			//End of page contents
			$this->state=1;
		}

		function _newobj() {
			//Begin a new object
			$this->n++;
			$this->offsets[$this->n]=strlen($this->buffer);
			$this->_out($this->n.' 0 obj');
		}

		function _dounderline($x,$y,$txt) {
			//Underline text
			$up=$this->CurrentFont['up'];
			$ut=$this->CurrentFont['ut'];
			$w=$this->GetStringWidth($txt)+$this->ws*substr_count($txt,' ');
			return sprintf('%.2f %.2f %.2f %.2f re f',$x*$this->k,($this->h-($y-$up/1000*$this->FontSize))*$this->k,$w*$this->k,-$ut/1000*$this->FontSizePt);
		}

		function _parsejpg($file) {
			//Extract info from a JPEG file
			$a=GetImageSize($file);
			if(!$a) $this->Error('Missing or incorrect image file: '.$file);
			if($a[2]!=2) $this->Error('Not a JPEG file: '.$file);
			if(!isset($a['channels']) || $a['channels']==3) $colspace='DeviceRGB';
			elseif($a['channels']==4) $colspace='DeviceCMYK';
			else $colspace='DeviceGray';
			$bpc=isset($a['bits']) ? $a['bits'] : 8;
			//Read whole file
			$f=fopen($file,'rb');
			$data='';
			while(!feof($f)) $data.=fread($f,4096);
			fclose($f);
			return array('w'=>$a[0],'h'=>$a[1],'cs'=>$colspace,'bpc'=>$bpc,'f'=>'DCTDecode','data'=>$data);
		}

	
		
		/*function _parsepng($file) {
			//Extract info from a PNG file
			$f=fopen($file,'rb');
			if(!$f) $this->Error('Can\'t open image file: '.$file);
			//Check signature
			if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10)) $this->Error('Not a PNG file: '.$file);
			//Read header chunk
			fread($f,4);
			if(fread($f,4)!='IHDR') $this->Error('Incorrect PNG file: '.$file);
			$w=$this->_freadint($f);
			$h=$this->_freadint($f);
			$bpc=ord(fread($f,1));
			if($bpc>8) $this->Error('16-bit depth not supported: '.$file);
			$ct=ord(fread($f,1));
			if($ct==0) $colspace='DeviceGray';
			elseif($ct==2) $colspace='DeviceRGB';
			elseif($ct==3) $colspace='Indexed';
			else $this->Error('Alpha channel not supported: '.$file);
			if(ord(fread($f,1))!=0) $this->Error('Unknown compression method: '.$file);
			if(ord(fread($f,1))!=0) $this->Error('Unknown filter method: '.$file);
			if(ord(fread($f,1))!=0) $this->Error('Interlacing not supported: '.$file);
			fread($f,4);
			$parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
			//Scan chunks looking for palette, transparency and image data
			$pal='';
			$trns='';
			$data='';
			do {
				$n=$this->_freadint($f);
				$type=fread($f,4);
				if($type=='PLTE') {
					//Read palette
					$pal=fread($f,$n);
					fread($f,4);
				}
				elseif($type=='tRNS') {
					//Read transparency info
					$t=fread($f,$n);
					if($ct==0) $trns=array(ord(substr($t,1,1)));
					elseif($ct==2) $trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
					else {
						$pos=strpos($t,chr(0));
						if($pos!==false) $trns=array($pos);
					}
					fread($f,4);
				}
				elseif($type=='IDAT') {
					//Read image data block
					$data.=fread($f,$n);
					fread($f,4);
			}
			elseif($type=='IEND') break;
			else fread($f,$n+4);
		}
		while($n); 
			if($colspace=='Indexed' && empty($pal)) $this->Error('Missing palette in '.$file);
			fclose($f);
			return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
		}*/
		
		function _parsepng($file)
		{
			//Extract info from a PNG file
			$f=fopen($file,'rb');
			if(!$f)
				$this->Error('Can\'t open image file: '.$file);
			//Check signature
			if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
				$this->Error('Not a PNG file: '.$file);
			//Read header chunk
			fread($f,4);
			if(fread($f,4)!='IHDR')
				$this->Error('Incorrect PNG file: '.$file);
			$w=$this->_readint($f);
			$h=$this->_readint($f);
			$bpc=ord(fread($f,1));
			if($bpc>8)
				$this->Error('16-bit depth not supported: '.$file);
			$ct=ord(fread($f,1));
			if($ct==0)
				$colspace='DeviceGray';
			elseif($ct==2)
				$colspace='DeviceRGB';
			elseif($ct==3)
				$colspace='Indexed';
			else {
				fclose($f);      // the only changes are 
				return 'alpha';  // made in those 2 lines
			}
			if(ord(fread($f,1))!=0)
				$this->Error('Unknown compression method: '.$file);
			if(ord(fread($f,1))!=0)
				$this->Error('Unknown filter method: '.$file);
			if(ord(fread($f,1))!=0)
				$this->Error('Interlacing not supported: '.$file);
			fread($f,4);
			$parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
			//Scan chunks looking for palette, transparency and image data
			$pal='';
			$trns='';
			$data='';
			do
			{
				$n=$this->_readint($f);
				$type=fread($f,4);
				if($type=='PLTE')
				{
					//Read palette
					$pal=fread($f,$n);
					fread($f,4);
				}
				elseif($type=='tRNS')
				{
					//Read transparency info
					$t=fread($f,$n);
					if($ct==0)
						$trns=array(ord(substr($t,1,1)));
					elseif($ct==2)
						$trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
					else
					{
						$pos=strpos($t,chr(0));
						if($pos!==false)
							$trns=array($pos);
					}
					fread($f,4);
				}
				elseif($type=='IDAT')
				{
					//Read image data block
					$data.=fread($f,$n);
					fread($f,4);
				}
				elseif($type=='IEND')
					break;
				else
					fread($f,$n+4);
			}
			while($n);
			if($colspace=='Indexed' && empty($pal))
				$this->Error('Missing palette in '.$file);
			fclose($f);
			return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
		}

		function _freadint($f) {
			//Read a 4-byte integer from file
			$a=unpack('Ni',fread($f,4));
			return $a['i'];
		}

		function _textstring($s) {
			//Format a text string
			return '('.$this->_escape($s).')';
		}

		function _escape($s) {
			//Add \ before \, ( and )
			return str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$s)));
		}

		function _putstream($s) {
			$this->_out('stream');
			$this->_out($s);
			$this->_out('endstream');
		}

		function _out($s) {
			//Add a line to the document
			if($this->state==2) $this->pages[$this->page].=$s."\n";
			else $this->buffer.=$s."\n";
		}
		function SetThaiFont(){
			$this->AddFont('AngsanaNew','','angsa.php');
			$this->AddFont('AngsanaNew','B','angsab.php');
			$this->AddFont('AngsanaNew','I','angsai.php');
			$this->AddFont('AngsanaNew','IB','angsaz.php');
			$this->AddFont('CordiaNew','','cordia.php');
			$this->AddFont('CordiaNew','B','cordiab.php');
			$this->AddFont('CordiaNew','I','cordiai.php');
			$this->AddFont('CordiaNew','IB','cordiaz.php');
			$this->AddFont('Tahoma','','tahoma.php');
			$this->AddFont('Tahoma','B','tahomab.php');
			$this->AddFont('BrowalliaNew','','browa.php');
			$this->AddFont('BrowalliaNew','B','browab.php');
			$this->AddFont('BrowalliaNew','I','browai.php');
			$this->AddFont('BrowalliaNew','IB','browaz.php');
			$this->AddFont('KoHmu','','kohmu.php');
			$this->AddFont('KoHmu2','','kohmu2.php');
			$this->AddFont('KoHmu3','','kohmu3.php');
			$this->AddFont('MicrosoftSansSerif','','micross.php');
			$this->AddFont('PLE_Cara','','plecara.php');
			$this->AddFont('PLE_Care','','plecare.php');
			$this->AddFont('PLE_Care','B','plecareb.php');
			$this->AddFont('PLE_Joy','','plejoy.php');
			$this->AddFont('PLE_Tom','','pletom.php');
			$this->AddFont('PLE_Tom','B','pletomb.php');
			$this->AddFont('PLE_TomOutline','','pletomo.php');
			$this->AddFont('PLE_TomWide','','pletomw.php');
			$this->AddFont('DilleniaUPC','','dill.php');
			$this->AddFont('DilleniaUPC','B','dillb.php');
			$this->AddFont('DilleniaUPC','I','dilli.php');
			$this->AddFont('DilleniaUPC','IB','dillz.php');
			$this->AddFont('EucrosiaUPC','','eucro.php');
			$this->AddFont('EucrosiaUPC','B','eucrob.php');
			$this->AddFont('EucrosiaUPC','I','eucroi.php');
			$this->AddFont('EucrosiaUPC','IB','eucroz.php');
			$this->AddFont('FreesiaUPC','','free.php');
			$this->AddFont('FreesiaUPC','B','freeb.php');
			$this->AddFont('FreesiaUPC','I','freei.php');
			$this->AddFont('FreesiaUPC','IB','freez.php');
			$this->AddFont('IrisUPC','','iris.php');
			$this->AddFont('IrisUPC','B','irisb.php');
			$this->AddFont('IrisUPC','I','irisi.php');
			$this->AddFont('IrisUPC','IB','irisz.php');
			$this->AddFont('JasmineUPC','','jasm.php');
			$this->AddFont('JasmineUPC','B','jasmb.php');
			$this->AddFont('JasmineUPC','I','jasmi.php');
			$this->AddFont('JasmineUPC','IB','jasmz.php');
			$this->AddFont('KodchiangUPC','','kodc.php');
			$this->AddFont('KodchiangUPC','B','kodc.php');
			$this->AddFont('KodchiangUPC','I','kodci.php');
			$this->AddFont('KodchiangUPC','IB','kodcz.php');
			$this->AddFont('LilyUPC','','lily.php');
			$this->AddFont('LilyUPC','B','lilyb.php');
			$this->AddFont('LilyUPC','I','lilyi.php');
			$this->AddFont('LilyUPC','IB','lilyz.php');
		}
		//End of class
	}
		
	//Handle special IE contype request
	if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']=='contype') {
		header('Content-Type: application/pdf');
		exit;
	}
}
?>
