--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.1
-- Dumped by pg_dump version 9.1.2
-- Started on 2012-02-13 11:00:41

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 6 (class 2615 OID 64371)
-- Name: account; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA account;


ALTER SCHEMA account OWNER TO dev;

--
-- TOC entry 7 (class 2615 OID 64372)
-- Name: carregis; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA carregis;


ALTER SCHEMA carregis OWNER TO dev;

--
-- TOC entry 8 (class 2615 OID 64373)
-- Name: corporate; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA corporate;


ALTER SCHEMA corporate OWNER TO dev;

--
-- TOC entry 14 (class 2615 OID 66416)
-- Name: finance; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA finance;


ALTER SCHEMA finance OWNER TO dev;

--
-- TOC entry 9 (class 2615 OID 64374)
-- Name: gas; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA gas;


ALTER SCHEMA gas OWNER TO dev;

--
-- TOC entry 10 (class 2615 OID 64375)
-- Name: insure; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA insure;


ALTER SCHEMA insure OWNER TO dev;

--
-- TOC entry 11 (class 2615 OID 64376)
-- Name: letter; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA letter;


ALTER SCHEMA letter OWNER TO dev;

--
-- TOC entry 12 (class 2615 OID 64377)
-- Name: pmain; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA pmain;


ALTER SCHEMA pmain OWNER TO dev;

--
-- TOC entry 13 (class 2615 OID 64378)
-- Name: refinance; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA refinance;


ALTER SCHEMA refinance OWNER TO dev;

--
-- TOC entry 454 (class 3079 OID 11638)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 4012 (class 0 OID 0)
-- Dependencies: 454
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = account, pg_catalog;

--
-- TOC entry 468 (class 1255 OID 64379)
-- Dependencies: 6 1714
-- Name: CheckTranferInYear(date, date, text); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "CheckTranferInYear"(chk_date date, p_cldate date, tranid text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

	
BEGIN
	if tranid = '' or tranid is null then 
		return false;
	else
		if extract(year from chk_date) = extract(year from p_cldate) then
			return true;
		else
			return false;
		end if;
	end if;

END;$$;


ALTER FUNCTION account."CheckTranferInYear"(chk_date date, p_cldate date, tranid text) OWNER TO dev;

--
-- TOC entry 469 (class 1255 OID 64380)
-- Dependencies: 6 1714
-- Name: CheckVatInMonth(integer, integer); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "CheckVatInMonth"(inmonth integer, inyear integer, OUT concludemsg text, OUT totalcus integer, OUT notfirst integer, OUT paybefore integer, OUT payinmonth integer, OUT cashcus integer, OUT errorcus integer, OUT memo text) RETURNS record
    LANGUAGE plpgsql
    AS $$DECLARE
	temfp "Fp"%ROWTYPE;
	temaccpayment "VAccPayment"%ROWTYPE;

	finddate date;
	countrec integer;
	nextmonth date;
BEGIN
-- parameter meaning  -----
-- IN inmonth   number of month use check vat
-- IN inyear    number of year use check vat
-- OUT concludemsg  display conclude message for check all table as "sucessful" ,"there is error"
-- OUT totalcus  all customer since past to this month
-- OUT notfirst number of contact which first due date not yet this month
-- OUT paybefore number of contact which pay installment befor month of due date that mean sent vat in last month 
-- OUT payinmonth number of contact which pay installment in month of due date
-- OUT cashcus number of contact which don't hire-purchase so there is not table for payment
-- OUT errorcus number of error in sent vat this month etc don't create accpaymnet , don't run vat in each day 
-- OUT memo use display idno of customer which has error in sent vat
-------------------------------------------------------------
-- there are 6 number of parameter
-- relation of them is 
-- totalcus = notfirst + paybefore + payinmonth + cashcus + errorcus 
-- number of record which sent in vat report = payinmonth + cashcus
-------------------------------------------------------------

	totalcus:=0;
	cashcus :=0;
	notfirst:=0;
	errorcus:=0;
	paybefore:=0;
	payinmonth:=0;
	memo:=chr(13);
	
	finddate := to_date(to_char(inyear,'9999') || '-' || trim(leading ' ' from to_char(inmonth,'09')) || '-01','YYYY-MM-DD');
	nextmonth:=finddate+ (1||'month')::interval;
	
	for temfp in select * from "Fp" where ( ("P_StopVatDate">=finddate)  or ("P_StopVatDate" is null) ) 
						and (("P_CLDATE">='2010-01-01')  or ("P_CLDATE" is null) )
						and ( "P_STDATE" < nextmonth )
						order by "IDNO" loop
		countrec:=0;
		totalcus := totalcus+1;
		if temfp."P_TOTAL" =0 then
			cashcus:=cashcus+1;
			select count(*) into countrec from "Fr" where "IDNO"=temfp."IDNO" and "R_DueNo"=99 and "Cancel"=false;
			if countrec=0 then 	
				memo := memo|| 'Vat lose cash customer : ' || temfp."IDNO" ||chr(13);
				errorcus:=errorcus+1;
			end if;
			
			if countrec > 1 then 	
				memo := memo|| 'There is Vat Recipt more than 1 : ' || temfp."IDNO" ||chr(13);
				errorcus:=errorcus+1;
			end if;
		else
			if temfp."P_DOWN" <> 0 then
				select count(*) into countrec from "Fr" where "IDNO"=temfp."IDNO" and "R_DueNo"=0 and "Cancel"=false;
				if countrec=0 then 	
					memo := memo|| 'Vat DownPayment lose : ' || temfp."IDNO" ||chr(13);
					errorcus:=errorcus+1;
				end if;
			
				if countrec > 1 then 	
					memo := memo|| 'There is Vat DownPayment Recipt more than 1 : ' || temfp."IDNO" ||chr(13);
					errorcus:=errorcus+1;
				end if;
			end if;  ---- temfp."P_DOWN" <> 0 
			
			select count(*) into countrec from "VAccPayment" where "IDNO"=temfp."IDNO";
			if countrec > 0 then
				select * into temaccpayment from "VAccPayment" where ("IDNO"=temfp."IDNO" ) and (EXTRACT(MONTH FROM "DueDate")=inmonth) and (EXTRACT(YEAR FROM "DueDate")=inyear);
				
				if found then
					if temaccpayment."V_Date" is null and  temfp."P_StopVatDate">temaccpayment."DueDate" then
						memo := memo|| 'Vat lose in month : ' || temfp."IDNO" ||chr(13);
						errorcus:=errorcus+1;
					else 
						if temaccpayment."V_Date" < finddate then
							paybefore := paybefore+1;
							-- memo:=memo||'pay before : ' ||temfp."IDNO" || chr(13);
						else 
							payinmonth:=payinmonth+1;
						end if;
					end if;
				else
				-- not yet first duedate 
					notfirst := notfirst+1;
				end if;
			else 
				memo := memo || 'not create accpayment : '||temfp."IDNO"||chr(13);
				errorcus := errorcus+1;
			end if;
			
		end if;  -- temfp."P_TOTAL" =0		 
		
	end loop;  -- for temfp
	if errorcus <> 0 then 
		concludemsg := 'There is error in Check '||finddate;
	else 
		concludemsg := 'Check VAT sucessful ';
	end if;
END;
$$;


ALTER FUNCTION account."CheckVatInMonth"(inmonth integer, inyear integer, OUT concludemsg text, OUT totalcus integer, OUT notfirst integer, OUT paybefore integer, OUT payinmonth integer, OUT cashcus integer, OUT errorcus integer, OUT memo text) OWNER TO dev;

--
-- TOC entry 471 (class 1255 OID 64381)
-- Dependencies: 1714 6
-- Name: CreateDebtBalance(date); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "CreateDebtBalance"(accldate date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfp "Fp"%ROWTYPE;
	timenotpay integer;
	timevatpayready integer;

	temidno text;
	temcusid text;
	temcusyear integer;
	total integer;
	payment double precision;
	vatinmonth double precision;

	
BEGIN
	--Clear All old debtbalance data for input account close date
	delete from account.debtbalance where ("acclosedate" = accldate);
	
	for temfp in select * from "Fp" where "P_TOTAL"<>0 and "P_STDATE"<=accldate and ("P_ACCLOSE"=false or "P_CLDATE">accldate) loop
		timenotpay :=0;
		timevatpayready :=0;
		
		temidno := temfp."IDNO";
		temcusid := temfp."CusID";
		temcusyear := temfp."P_CustByYear";
		total := temfp."P_TOTAL";
		payment := temfp."P_MONTH";
		vatinmonth := temfp."P_VAT";

		-- call function cal_debt_remain for find time of remain for each customer
		select into timenotpay,timevatpayready  debtremain,vatpaybefore from account.cal_debt_remain(accldate,temidno);
					
		INSERT INTO account.debtbalance("acclosedate","idno", "cusid", "custyear", "monthly","totaldue","notpaid","vatpayready") 
				values(accldate,temidno,temcusid,temcusyear,payment,total,timenotpay,vatinmonth*timevatpayready);
					
	end loop;
	RETURN true;

END;$$;


ALTER FUNCTION account."CreateDebtBalance"(accldate date) OWNER TO dev;

--
-- TOC entry 472 (class 1255 OID 64382)
-- Dependencies: 1714 6
-- Name: CreateEFTEndYear(date); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "CreateEFTEndYear"(accldate date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfp "Fp"%ROWTYPE;
	timenotpay integer;
	pv_end_date date;
	nextyear date;

	temidno text;
	temcusid text;
	has_transferid boolean;
	temcusyear integer;
	total integer;
	payment double precision;
	accbegin double precision;

	Larldueno integer;
	Larlaccint double precision;
	Larlwaitincome double precision;
	Lapaidno integer;
	Laondueno integer;

	Curldueno integer;
	Curlaccint double precision;
	Curlwaitincome double precision;
	Curlondue double precision;
	Cupaidno integer;
	Cuondueno integer;
	Rltotal double precision;
	
	Nxrlondue double precision;
	Ovdueno integer;
	Nxdueno integer;
	Otheryearno integer;

	ArOver double precision;
	ArNext double precision;
	ArOther double precision;
	ArAll double precision;

	unrlover double precision;
	unrlnext double precision;
	unrlother double precision;
	unrltotal double precision;

	stcomthisy double precision;
	lastycom double precision;
	thisycom double precision;
	nextycom double precision;

	aroutst double precision;
	aroutafter double precision;
	ratewroff integer;
	backupwroff double precision;
BEGIN
	--Clear All old effsoyaddcom data for input account close date
	delete from account.effsoyaddcom where ("acclosedate" = accldate);

	--assign value to variable from fp table
	pv_end_date := to_date(to_char(EXTRACT(YEAR FROM accldate)-1,'9999') || '-12-31','YYYY-MM-DD');
--	nextyear:=accldate + interval '12 month';
	nextyear := to_date(to_char(EXTRACT(YEAR FROM accldate)+1,'9999') || '-12-31','YYYY-MM-DD');
	
	for temfp in select * from "Fp" where "P_TOTAL"<>0 and "P_STDATE"<=accldate and ("P_ACCLOSE"=false or "P_CLDATE">pv_end_date ) loop
		timenotpay :=0;
		temidno := temfp."IDNO";
		temcusid := temfp."CusID";
		temcusyear := temfp."P_CustByYear";
		total := temfp."P_TOTAL";
		payment := temfp."P_MONTH";
		accbegin := temfp."P_BEGINX";
		
		Larldueno :=0;
		Larlaccint:=0;
		Larlwaitincome:=0;
		Lapaidno:=0;
		Laondueno:=0;
		
		Curldueno :=0;
		Curlaccint:=0;
		Curlwaitincome:=0;
		Curlondue:=0;
		Cupaidno:=0;
		Cuondueno:=0;	
		Rltotal:=0;

		Nxrlondue:=0;
			
		Ovdueno:=0;
		Nxdueno:=0;
		Otheryearno:=0;

		ArOver :=0;
		ArNext :=0;
		ArOther :=0;
		ArAll :=0;
			
		unrlover :=0;
		unrlnext :=0;
		unrlother :=0;
		unrltotal :=0;

		lastycom :=0;
		thisycom :=0;
		nextycom :=0;

		aroutst :=0;
		aroutafter:=0;
		ratewroff :=0;
		backupwroff :=0;
	
		----- initial value  new record of effsoyaddcom 

	
		has_transferid := account."CheckTranferInYear"(pv_end_date,temfp."P_CLDATE",temfp."P_TransferIDNO");
		select into Larlaccint,lastycom  accinter,acccom from account.call_due_rl(pv_end_date,temidno,has_transferid,temfp."P_CLDATE");
		
		has_transferid := account."CheckTranferInYear"(accldate,temfp."P_CLDATE",temfp."P_TransferIDNO");	
		select into Curldueno, Curlaccint, Curlwaitincome, Cupaidno, Cuondueno,Curlondue,thisycom  rldueno,accinter,waitinter,paidno,ondueno,rlondue,acccom from account.call_due_rl(accldate,temidno,has_transferid,temfp."P_CLDATE");

		has_transferid := account."CheckTranferInYear"(nextyear,temfp."P_CLDATE",temfp."P_TransferIDNO");	
		select into  Nxrlondue,unrlother  rlondue,waitrlondue from account.call_due_rl( nextyear ,temidno,has_transferid,temfp."P_CLDATE");

		Rltotal:=round(((payment*total)-accbegin)*100)/100;
		
		if Cuondueno>Cupaidno then
			Ovdueno:=Cuondueno-Cupaidno;
		else
			Ovdueno:=0;
		end if;
		if total-Cupaidno-Ovdueno > 12 then
			Nxdueno:= 12;
		else 
			Nxdueno:=total-Cupaidno-Ovdueno;
		end if;
		Otheryearno:=total-Cupaidno-Ovdueno-Nxdueno;

		ArOver:=Ovdueno*payment;
		ArNext:=Nxdueno*payment;
		ArOther:=Otheryearno*payment;
		ArAll:=ArOver+ArNext+ArOther;
	
		if round(cast(Curlondue as numeric),2)>round(cast(Curlaccint as numeric),2) then
			unrlover:=Curlondue-Curlaccint;
		else 
			unrlover:=0;
		end if;


		unrlnext:=Rltotal-Larlaccint-(Curlaccint-Larlaccint)-unrlover-(RlTotal-Nxrlondue);
--		unrlother:=round((Rltotal-Nxrlondue)*100)/100;
--		unrlother:=round((Curlwaitincome-(Nxrlondue-Curlaccint))*100)/100;

		if round(cast(Larlaccint+Curlaccint as numeric),2)=round(cast(Rltotal as numeric),2) then
			Nxrlondue:=0;
			unrlnext:=0;
			unrlother:=0;
		end if;	

		
		
		if accldate >= temfp."P_CLDATE" then
			Ovdueno:=0;
			Nxdueno:=0;
			ArOver:=0;
			ArNext:=0;
			ArOther:=0;
			ArAll:=0;
			Nxrlondue:=0;
			unrlover:=0;
			unrlnext:=0;
			unrlother:=0;
			nextycom:=0;
			if temfp."P_TransferIDNO" is null then
				thisycom:=temfp."Comm";
			end if;
		else
			nextycom:=temfp."Comm"-thisycom;
		end if;


		unrltotal := unrlover+unrlnext+unrlother;
		
		aroutst := ArNext+ArOther+ArOver;
		
		if Ovdueno <= 6 then
			aroutafter :=(aroutst-unrltotal)*20/100;
		else
			aroutafter :=(aroutst-unrltotal);
		end if;
		
		if Ovdueno >=0 and Ovdueno<2 then
			ratewroff:=1;
		elsif Ovdueno>=2 and Ovdueno<4 then
			ratewroff:=2;
		elsif Ovdueno>=4 and Ovdueno<7 then
			ratewroff:=20;
		elsif Ovdueno>=7 and Ovdueno<13 then
			ratewroff:=50;
		else ratewroff :=100;
		end if;
		backupwroff := aroutafter*ratewroff/100;
		
		INSERT INTO account.effsoyaddcom("acclosedate","idno", "cusid", "custyear", "paid", "overdue","nextydue","otherydue","totaldue","monthly","aroverdue","arnextydue","arotherydue","artotal",
						 "rlpreviousy","rltothisy","rlpayreal","rlnexty","rlall","rlthisy","uroverdue","urnexty","urothery","urtotal","tranid","duenoac","comlastyear","comaccthisyear","comnextyear",
						 "aroutstanding","aroutafterguarantee","writeoffrate","backupwriteoff") 
				values(accldate,temidno,temcusid,temcusyear,Cupaidno,Ovdueno,Nxdueno, Otheryearno,total,payment,ArOver,ArNext,ArOther,ArAll,
						 Larlaccint,Curlondue,Curlaccint,Nxrlondue,Rltotal,(Curlaccint-Larlaccint),unrlover,unrlnext,unrlother,unrltotal,temfp."P_TransferIDNO",Curldueno,lastycom,thisycom,nextycom,
						 aroutst,aroutafter,ratewroff,backupwroff);
					
	end loop;
	RETURN true;

END;$$;


ALTER FUNCTION account."CreateEFTEndYear"(accldate date) OWNER TO dev;

--
-- TOC entry 473 (class 1255 OID 64384)
-- Dependencies: 6 1714
-- Name: CreateRptVatBuy(integer, integer); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "CreateRptVatBuy"(mm integer, yy integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temacb account."VAccountBook"%ROWTYPE;
	temvdname text;
	temvdacid text;
	costofvalue double precision;
	
BEGIN
	--Clear All old RptVatBuy data for input month(mm) and year(yy) 
	delete from account."RptVatBuy" where (extract(month from "v_date")=mm and extract(year from "v_date")=yy );

	for temacb in select * from account."VAccountBook" where (extract(month from "acb_date")=mm and extract(year from "acb_date")=yy and "AcID"='1999') loop
	
		select into costofvalue account."AccountBookDetail"."AmtDr" from account."AccountBookDetail" where temacb.auto_id = "autoid_abh" and "AmtDr" <>0;
		select into temvdacid account."AccountBookDetail"."AcID" from account."AccountBookDetail" where temacb.auto_id = "autoid_abh" and "AmtCr" <>0;
		select into temvdname account.vender.vd_name from account.vender where acid = temvdacid;

		insert into account."RptVatBuy" values (temacb."acb_date",temacb."acb_id",temacb."RefID",temvdname,costofvalue,temacb."AmtDr");
	end loop;
	
	RETURN true;

END;$$;


ALTER FUNCTION account."CreateRptVatBuy"(mm integer, yy integer) OWNER TO dev;

--
-- TOC entry 474 (class 1255 OID 64385)
-- Dependencies: 6 1714
-- Name: cal_debt_remain(date, text); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION cal_debt_remain(closedate date, idno text, OUT debtremain integer, OUT vatpaybefore integer) RETURNS record
    LANGUAGE plpgsql
    AS $$DECLARE
	temaccpayment "VAccPayment"%ROWTYPE;

BEGIN
	-- debtremain = จำนวนงวดที่ค้างชำระทั้งหมด
	-- vatpaybefore = จำนวนงวดของภาษีมูลค่าเพิ่มที่ชำระให้ลูกค้าก่อน แต่ลูกค้ายังไม่ชำระค่างวดให้บริษัท

	debtremain :=0;
	vatpaybefore :=0;
	
	for temaccpayment in select * from "VAccPayment" where "IDNO"=idno loop
		if (temaccpayment."R_Date"<=closedate or temaccpayment."V_Date"<=closedate)  then
				
			if temaccpayment."R_Date" is null or temaccpayment."R_Date" > closedate then
				vatpaybefore := vatpaybefore+1;
				debtremain := debtremain+1;
			end if;
		else
			-- r_date > closedate and v_date > closedate (check debtremain only) or
			-- r_date is null and v_date is null (check debtremain only) or
			-- r_date is null and v_date > closedate (check debtremain only) or

			debtremain := debtremain+1;
		end if;
	end loop;

END;
$$;


ALTER FUNCTION account.cal_debt_remain(closedate date, idno text, OUT debtremain integer, OUT vatpaybefore integer) OWNER TO dev;

--
-- TOC entry 475 (class 1255 OID 64386)
-- Dependencies: 1714 6
-- Name: call_due_rl(date, text, boolean, date); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION call_due_rl(closedate date, idno text, tranid boolean, p_cldate date, OUT rldueno integer, OUT accinter double precision, OUT waitinter double precision, OUT paidno integer, OUT ondueno integer, OUT rlondue double precision, OUT waitrlondue double precision, OUT acccom double precision) RETURNS record
    LANGUAGE plpgsql
    AS $$DECLARE
	temaccpayment "VAccPayment"%ROWTYPE;
	timenotpay integer=0;
BEGIN
	paidno:=0;
	ondueno:=0;
	for temaccpayment in select * from "VAccPayment" where "IDNO"=idno loop
		if (temaccpayment."R_Date"<=closedate or temaccpayment."DueDate"<=closedate) and timenotpay < 3 then

--	for temaccpayment in select * from "VAccPayment" 
--	    where "IDNO"=idno and (temaccpayment."R_Date"<=closedate or temaccpayment."DueDate"<=closedate) and timenotpay < 3 loop

			 	
			if ((temaccpayment."R_Date" is null) or (temaccpayment."R_Date" > closedate) ) then
				if tranid = false  then
					timenotpay := timenotpay+1;
				else
					exit;
				end if;
				
				if temaccpayment."DueDate">p_cldate  and p_cldate is not null then
					exit;
				end if;
			else 
				paidno := temaccpayment."DueNo";
			end if;
		
			rldueno := temaccpayment."DueNo";
			accinter:= temaccpayment.accint;
			waitinter:= temaccpayment.waitincome;
			acccom := temaccpayment.commaccu;
		end if;
--		if temaccpayment."DueDate"<=closedate then
--				ondueno:=temaccpayment."DueNo";
--				rlondue:=temaccpayment.accint;
--		end if;			
	end loop;  -- for temaccpayment

	select  count(*) into ondueno from "VAccPayment" where "IDNO" = idno and "DueDate" <= closedate ;
	select into rlondue,waitrlondue   accint,waitincome from "VAccPayment" where "IDNO" = idno and "DueNo" = ondueno;
	
	
	if rldueno is null then rldueno:=0; end if;
	if accinter is null then accinter:=0; end if;
	if waitinter is null then waitinter:=0; end if;
	if paidno is null then paidno:=0; end if;
	if ondueno is null then ondueno:=0; end if;
	if rlondue is null then rlondue:=0; end if;
	if waitrlondue is null then waitrlondue:=0; end if;	
	if acccom is null then acccom:=0; end if;	
END;
$$;


ALTER FUNCTION account.call_due_rl(closedate date, idno text, tranid boolean, p_cldate date, OUT rldueno integer, OUT accinter double precision, OUT waitinter double precision, OUT paidno integer, OUT ondueno integer, OUT rlondue double precision, OUT waitrlondue double precision, OUT acccom double precision) OWNER TO dev;

--
-- TOC entry 476 (class 1255 OID 64387)
-- Dependencies: 6 1714
-- Name: gen_no(date, text); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION gen_no(datein date, gentype text) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
    dd integer;
    mm integer;
    yy integer;

    temm integer;
    temdate text:='';
    outputmsg text:='';
BEGIN
    dd:=EXTRACT(DAY FROM datein);
    mm:=EXTRACT(MONTH FROM datein);
    yy:=EXTRACT(YEAR FROM datein);

    if gentype='GJ' then
    
        select into temm  account."RunningNo"."GJ"
        from     account."RunningNo" Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);

        if not found  then    
            temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
            insert into account."RunningNo" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0);
            temm:=0;
        end if;
        
        update account."RunningNo" set  "GJ"="GJ"+1 Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);
        outputmsg :='GJ' || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temm+1,'FM009');
        
    elsif gentype='RC' then

        select into temm  account."RunningNo"."RC"
        from     account."RunningNo" Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);

        if not found  then    
            temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
            insert into account."RunningNo" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0);
            temm:=0;
        end if;
        
        update account."RunningNo" set  "RC"="RC"+1 Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);
        outputmsg :='RC' || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temm+1,'FM009');

    elsif gentype='PC' then

        select into temm account."RunningNo"."PC"
        from     account."RunningNo" Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);

        if not found  then    
            temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
            insert into account."RunningNo" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0);
            temm:=0;
        end if;
        
        update account."RunningNo" set  "PC"="PC"+1 Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);
        outputmsg :='PC' || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temm+1,'FM009');

    elsif gentype='VR' then
    
        select into temm  account."RunningNo"."VR"
        from     account."RunningNo" Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);

        if not found  then    
            temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
            insert into account."RunningNo" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0);
            temm:=0;
        end if;
        
        update account."RunningNo" set  "VR"="VR"+1 Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);
        outputmsg :='VR' || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temm+1,'FM009');

    elsif gentype='VP' then
    
        select into temm  account."RunningNo"."VP"
        from     account."RunningNo" Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);

        if not found  then    
            temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
            insert into account."RunningNo" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0);
            temm:=0;
        end if;
        
        update account."RunningNo" set  "VP"="VP"+1 Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);
        outputmsg :='VP' || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temm+1,'FM009');

    elsif gentype='AJ' then
    
        select into temm  account."RunningNo"."AJ"
        from     account."RunningNo" Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);

        if not found  then    
            temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
            insert into account."RunningNo" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0);
            temm:=0;
        end if;
        
        update account."RunningNo" set  "AJ"="AJ"+1 Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);
        outputmsg :='AJ' || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temm+1,'FM009');

    elsif gentype='AP' then
    
        select into temm  account."RunningNo"."AP"
        from     account."RunningNo" Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);

        if not found  then    
            temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
            insert into account."RunningNo" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0);
            temm:=0;
        end if;
        
        update account."RunningNo" set  "AP"="AP"+1 Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);
        outputmsg :='AP' || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temm+1,'FM009');

    elsif gentype='IGJ' then
    
        select into temm  account."RunningNo"."IGJ"
        from     account."RunningNo" Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);

        if not found  then    
            temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
            insert into account."RunningNo" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0);
            temm:=0;
        end if;
        
        update account."RunningNo" set  "IGJ"="IGJ"+1 Where (extract(month from account."RunningNo"."RunningDate")=mm and extract(year from account."RunningNo"."RunningDate")=yy and extract(day from account."RunningNo"."RunningDate")=dd);
        outputmsg :='IGJ' || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temm+1,'FM009');

   end if;

    RETURN outputmsg;

    
END;
$$;


ALTER FUNCTION account.gen_no(datein date, gentype text) OWNER TO dev;

--
-- TOC entry 477 (class 1255 OID 64388)
-- Dependencies: 1714 6
-- Name: gen_payid(date); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION gen_payid(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	mm integer;
	yy integer;
	
	tempayid integer;
	temid text:='';
	temdate text := '';

BEGIN
-- parameter with sent in function
-- datein  use month and year which key gas in system

	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	select into tempayid  account."PayID"."payid"
	from account."PayID" Where (extract(month from account."PayID"."monthid")=mm and extract(year from account."PayID"."monthid")=yy );

	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(1,'99');
		insert into account."PayID" values (1,to_date(temdate,'YYYY-MM-DD') ,0,0);
		tempayid:=0;
	end if;

	update account."PayID" set "payid"=tempayid+1 Where (extract(month from account."PayID"."monthid")=mm and extract(year from account."PayID"."monthid")=yy ); 
	temid :='PCN'  || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(tempayid+1,'FM009');

	RETURN temid;

END;

$$;


ALTER FUNCTION account.gen_payid(datein date) OWNER TO dev;

--
-- TOC entry 470 (class 1255 OID 64389)
-- Dependencies: 6 1714
-- Name: save_acc_gas_admin(date, text, double precision, double precision, text, text); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION save_acc_gas_admin(cqdate date, cqid text, costofgas double precision, vatofcost double precision, poid text, company text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

	temid text;
	temcompany text;
	auto_id integer;
	
BEGIN	
	temid := account.gen_no(cqdate,'GJ'); -- GenID

	INSERT INTO account."AccountBookHead"("type_acb", "acb_id", "acb_date", "acb_detail")values('GJ',temid,cqdate,'ชำระค่าถังแก๊ส โดยจ่ายเช็คธนาคารทหารไทย (กระแสรายวัน) เลขที่ ' || cqid);

	SELECT into auto_id currval('account."AccountBookHead_auto_id_seq"');

	SELECT INTO temcompany gas."Company"."acid" FROM gas."Company" WHERE (coid=company);

	INSERT INTO account."AccountBookDetail"("autoid_abh", "AcID", "AmtDr", "AmtCr", "RefID")values(auto_id,temcompany,(costofgas+vatofcost),'0',poid);
	INSERT INTO account."AccountBookDetail"("autoid_abh", "AcID", "AmtDr", "AmtCr", "RefID")values(auto_id,'1003','0',(costofgas+vatofcost),cqid);

	RETURN true;

END;$$;


ALTER FUNCTION account.save_acc_gas_admin(cqdate date, cqid text, costofgas double precision, vatofcost double precision, poid text, company text) OWNER TO dev;

--
-- TOC entry 478 (class 1255 OID 64390)
-- Dependencies: 6 1714
-- Name: save_acc_gas_maker(date, text, double precision, double precision, text, text); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION save_acc_gas_maker(vatdate date, poid text, costofgas double precision, vatofcost double precision, invoice text, company text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

	temid text;
	temcompany text;
	auto_id integer;
	
BEGIN	
	temid := account.gen_no(vatdate,'GJ'); -- GenID

	INSERT INTO account."AccountBookHead"("type_acb", "acb_id", "acb_date", "acb_detail","ref_id")values('GJ',temid,vatdate,'ตั้งเจ้าหนี้ถังแก๊ส ' || poid,'VATB');

	SELECT INTO auto_id currval('account."AccountBookHead_auto_id_seq"');

	INSERT INTO account."AccountBookDetail"("autoid_abh", "AcID", "AmtDr", "AmtCr", "RefID")values(auto_id,'1613',costofgas,'0',poid);
	INSERT INTO account."AccountBookDetail"("autoid_abh", "AcID", "AmtDr", "AmtCr", "RefID")values(auto_id,'1999',vatofcost,'0',invoice);
	
	SELECT INTO temcompany gas."Company"."acid" FROM gas."Company" WHERE (coid=company);
	
	INSERT INTO account."AccountBookDetail"("autoid_abh", "AcID", "AmtDr", "AmtCr", "RefID")values(auto_id,temcompany,'0',(costofgas+vatofcost),poid);
	
	RETURN true;

END;$$;


ALTER FUNCTION account.save_acc_gas_maker(vatdate date, poid text, costofgas double precision, vatofcost double precision, invoice text, company text) OWNER TO dev;

--
-- TOC entry 572 (class 1255 OID 68132)
-- Dependencies: 6 1714
-- Name: thcap_get_nextRunningID(character varying, character varying, character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_get_nextRunningID"(vcompid character varying, vfieldname character varying, prefix character varying DEFAULT ''::character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$DECLARE

	old_ID bigint;

BEGIN

	SELECT INTO old_ID
		public."thcap_running_number"."runningNum"
	FROM 
		public."thcap_running_number"
	WHERE
		"compID" = vCompID AND
		"fieldName" = vFieldName;

	return prefix || replace( to_char(old_ID + 1, '9999999999'), ' ', '0');

END;$$;


ALTER FUNCTION account."thcap_get_nextRunningID"(vcompid character varying, vfieldname character varying, prefix character varying) OWNER TO dev;

--
-- TOC entry 4013 (class 0 OID 0)
-- Dependencies: 572
-- Name: FUNCTION "thcap_get_nextRunningID"(vcompid character varying, vfieldname character varying, prefix character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION "thcap_get_nextRunningID"(vcompid character varying, vfieldname character varying, prefix character varying) IS '
คำอธิบาย:
---------------------------
function สำหรับหาค่าเลขที่ running ID ว่างเลขถัดไปเพื่อนำไปใช้เพิ่ม ID ของ field ต่างๆ

ใส่ค่า:
---------------------------
ชื่อบริษัทที่ต้องการ,
รหัส field ที่ต้องการ,
prefix หน้า ID

เช่น
SELECT account."thcap_get_nextRunningID"(
    ''THCAP'',
    ''revTranID'',
    ''RT''
);


ค่า Return:
---------------------------
return เป็น string/varchar เลข running ที่นำไปใช้ได้เลย เช่น ตามตัวอย่างข้างต้น
RT0000000012';


--
-- TOC entry 479 (class 1255 OID 69069)
-- Dependencies: 1714 6
-- Name: thcap_ins_dncn(character varying, character varying, character varying, date, character varying, numeric, numeric, numeric, character varying, timestamp without time zone, character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION thcap_ins_dncn(vdctype character varying, vdccompid character varying, vinvoiceid character varying, vdcdate date, vdcdesc character varying, vdcamt numeric, vdcvatrate numeric, vdcamtvat numeric, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$DECLARE

DCID varchar;
ins_dncn_action boolean;
temp_next_running boolean;

BEGIN

	-- Declare variables
	ins_dncn_action = TRUE;


	-- ตรวจสอบว่าเป็น DN (Debit Note) หรือ CN (Credit Note) และนำเลขที่ Running ตามหมวดหมู่มาใช้
	IF vDCType = 'DN' THEN
		SELECT INTO DCID account."thcap_get_nextRunningID"('THCAP','DNID','DN');
	END IF;
	
	IF vDCType = 'CN' THEN
		SELECT INTO DCID account."thcap_get_nextRunningID"('THCAP','CNID','CN');
	END IF;

	IF vDCType = 'INV' THEN
		DCID = vInvoiceID;
	END IF;


	-- นำค่าที่ได้ใส่ใน table account.thcap_dncn
	INSERT INTO account.thcap_dncn(
			"dcNoteID",
			"dcType",
			"dcCompID",
			"invoiceID",
			"dcNoteDate",
			"dcNoteDescription", 
			"dcNoteAmt",
			"dcNoteVATRate",
			"dcNoteAmtVAT")
		VALUES (DCID,
			vDCType,
			vDCCompID,
			vInvoiceID,
			vDCDate,
			vDCDesc,
			vDCAmt,
			vDCVATRate,
			vDCAmtVAT
		);
		

	-- ตรวจสอบว่า Doer มี Remask หรือไม่ ถ้าไม่มีให้เป็น NULL
	IF vDoerRemask = '' THEN
		vDoerRemask = NULL;
	END IF;


	-- นำค่าที่ได้ใส่ใน table account.thcap_ins_dncn_action
	SELECT INTO ins_dncn_action account.thcap_ins_dncn_action(
	    'I',
	    DCID,
	    '1',
	    vDoerID,
	    vDoerStamp,
	    vDoerRemask
	);


	-- กลับไป UPDATE ค่า running ให้เป็นค่าถัดไป
	-- todo: ไม่แน่ใจว่าอนาคตจะมีปัญหาเรื่อง transaction หรือไม่ เพราะไม่มีการ check เลขเดิมก่อนบวกเพิ่มเข้าไป แต่คิดว่าน่าจะไม่มีปัญหาอะไร
	IF vDCType = 'DN' THEN
		SELECT INTO temp_next_running account."thcap_set_nextRunningID"(
		    vDCCompID,
		    'DNID'
		);
	END IF;

	IF vDCType = 'CN' THEN
		SELECT INTO temp_next_running account."thcap_set_nextRunningID"(
		    vDCCompID,
		    'CNID'
		);
	END IF;
	
	return DCID;

END;$$;


ALTER FUNCTION account.thcap_ins_dncn(vdctype character varying, vdccompid character varying, vinvoiceid character varying, vdcdate date, vdcdesc character varying, vdcamt numeric, vdcvatrate numeric, vdcamtvat numeric, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) OWNER TO dev;

--
-- TOC entry 4014 (class 0 OID 0)
-- Dependencies: 479
-- Name: FUNCTION thcap_ins_dncn(vdctype character varying, vdccompid character varying, vinvoiceid character varying, vdcdate date, vdcdesc character varying, vdcamt numeric, vdcvatrate numeric, vdcamtvat numeric, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION thcap_ins_dncn(vdctype character varying, vdccompid character varying, vinvoiceid character varying, vdcdate date, vdcdesc character varying, vdcamt numeric, vdcvatrate numeric, vdcamtvat numeric, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) IS 'ใช้สำหรับสร้าง Debit Note หรือ Credit Note ขึ้นมา 1 รายการโดยจะไปสร้างใน thcap_dncn และ thcap_dncn_action ให้อัตโนมัติ

--- ตัดล่างนี้ออก ---
และการเรียก Function นี้ถ้าไม่ใช่เป็นการสร้างพร้อมกับ invoice ใหม่ จะต้องไป UPDATE ยอดที่เปลี่ยนใน invoice ด้วย (ถ้าอนุมัติ)';


--
-- TOC entry 574 (class 1255 OID 68139)
-- Dependencies: 6 1714
-- Name: thcap_ins_dncn_action(character varying, character varying, smallint, character varying, timestamp without time zone, character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION thcap_ins_dncn_action(vdcactiontype character varying, dcid character varying, vserialaction smallint, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

BEGIN

	IF vDCActionType = 'I' THEN
		vSerialAction = 1;
	END IF;

	INSERT INTO account.thcap_dncn_action(
			"dcNoteActionType",
			"dcNoteID",
			"serialAction",
			"doerID",
			"doerStamp", 
			"doerRemask")
		VALUES (vDCActionType,
			DCID,
			vSerialAction,
			vDoerID,
			vDoerStamp,
			vDoerRemask
		);

	return TRUE;

END;$$;


ALTER FUNCTION account.thcap_ins_dncn_action(vdcactiontype character varying, dcid character varying, vserialaction smallint, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) OWNER TO dev;

--
-- TOC entry 4015 (class 0 OID 0)
-- Dependencies: 574
-- Name: FUNCTION thcap_ins_dncn_action(vdcactiontype character varying, dcid character varying, vserialaction smallint, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION thcap_ins_dncn_action(vdcactiontype character varying, dcid character varying, vserialaction smallint, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) IS '
ใช้สำหรับสร้าง thcap_dncn_action ตามข้อมูลที่ใส่ใน function';


--
-- TOC entry 577 (class 1255 OID 69169)
-- Dependencies: 6 1714
-- Name: thcap_ins_invoice(character varying, character varying, character varying, date, date, character varying, character varying, character varying, character varying, numeric, numeric, numeric, numeric, numeric, character varying, timestamp without time zone, character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION thcap_ins_invoice(vcontractid character varying, vcontracttype character varying, vinvcompid character varying, vinvdate date, vinvduedate date, vinvtypepay character varying, vinvtypepayref character varying, vinvidbefore character varying, vinvdesc character varying, vinvamt numeric, vinvvatrate numeric, vinvamtvat numeric, vinvwhtrate numeric, vinvamtwht numeric, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$DECLARE

INVID varchar;
DNCN varchar;
ins_invoice_action boolean;
temp_next_running boolean;
ableWHT smallint;

BEGIN

	-- Declare variables
	ins_invoice_action  = TRUE;

	
	-- ตรวจสอบว่า ประเภทสินเชื่อ/บริการ ของบริษัทที่ระบุ มี WHT หรือไม่
	SELECT INTO ableWHT
		"proAbleWHT"
	FROM 
		public."thcap_productType"
	WHERE 
		"proID" = vContractType AND
		"proCompany" = vInvCompID;

	-- ตรวจสอบว่าข้อมูลบางอย่างที่เป็น NULL ได้จะให้เป็น NULL หรือไม่ หรือถ้ารับค่าเป็น NULL แต่เป็น NULL ไม่ได้ต้องเป็น 0.00
	IF vInvIDBefore = '' THEN
		vInvIDBefore = NULL;
	END IF;

	IF vInvDesc = '' THEN
		vInvDesc = NULL;
	END IF;

	IF vDoerRemask = '' THEN
		vDoerRemask = NULL;
	END IF;

	IF vInvAmtVAT is NULL THEN
		vInvAmtVAT = 0.00;
	END IF;

	IF ableWHT = '0' THEN
		vInvWHTRate = NULL;
		vInvAmtWHT = 0.00;
	END IF;


	-- หาค่า INVID
	SELECT INTO INVID account."thcap_get_nextRunningID"(vInvCompID,'invoiceID','INV');


	-- นำค่าที่ได้ใส่ใน table account.thcap_invoice
	INSERT INTO account.thcap_invoice(
			"invoiceID",
			"contractID",
			"contractType",
			"invoiceCompID",
			"invoiceDate",
			"invoiceDueDate",
			"invoiceTypePay",
			"invoiceTypePayRef",
			"invoiceIDBefore",
			"invoiceDescription",
			"invoiceAmt",
			"invoiceVATRate",
			"invoiceAmtVAT",
			"invoiceWHTRate",
			"invoiceAmtWHT",
			"invoiceAmtLeft")
		VALUES (INVID,
			vContractID,
			vContractType,
			vInvCompID,
			vInvDate,
			vInvDueDate,
			vInvTypePay,
			vInvTypePayRef,
			vInvIDBefore,
			vInvDesc,
			vInvAmt, 
			vInvVATRate,
			vInvAmtVAT,
			vInvWHTRate,
			vInvAmtWHT,
			vInvAmt
		);


	-- นำค่าที่ได้ใส่ใน table account.thcap_ins_invoice_action ทำ action ของ invoice ขึ้นมา
	SELECT INTO ins_invoice_action account.thcap_ins_invoice_action(
	    'I',
	    INVID,
	    '1',
	    vDoerID,
	    vDoerStamp,
	    vDoerRemask
	);


	-- นำค่าที่ได้ใส่ใน thcap_ins_dncn ทำใบ debit note ขึ้นมา
	/*SELECT INTO DNCN account.thcap_ins_dncn(
	    'INV',
	    vInvCompID,
	    INVID,
	    vInvDate,
	    vInvDesc,
	    vInvAmt,
	    vInvVATRate,
	    vInvAmtVAT,
	    vDoerID,
	    vDoerStamp,
	    vDoerRemask
	);*/

	-- กลับไป UPDATE ค่า running ให้เป็นค่าถัดไป
	-- todo: ไม่แน่ใจว่าอนาคตจะมีปัญหาเรื่อง transaction หรือไม่ เพราะไม่มีการ check เลขเดิมก่อนบวกเพิ่มเข้าไป แต่คิดว่าน่าจะไม่มีปัญหาอะไร
	SELECT INTO temp_next_running account."thcap_set_nextRunningID"(
	    vInvCompID,
	    'invoiceID'
	);
	
	return INVID;

END;$$;


ALTER FUNCTION account.thcap_ins_invoice(vcontractid character varying, vcontracttype character varying, vinvcompid character varying, vinvdate date, vinvduedate date, vinvtypepay character varying, vinvtypepayref character varying, vinvidbefore character varying, vinvdesc character varying, vinvamt numeric, vinvvatrate numeric, vinvamtvat numeric, vinvwhtrate numeric, vinvamtwht numeric, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) OWNER TO dev;

--
-- TOC entry 4016 (class 0 OID 0)
-- Dependencies: 577
-- Name: FUNCTION thcap_ins_invoice(vcontractid character varying, vcontracttype character varying, vinvcompid character varying, vinvdate date, vinvduedate date, vinvtypepay character varying, vinvtypepayref character varying, vinvidbefore character varying, vinvdesc character varying, vinvamt numeric, vinvvatrate numeric, vinvamtvat numeric, vinvwhtrate numeric, vinvamtwht numeric, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION thcap_ins_invoice(vcontractid character varying, vcontracttype character varying, vinvcompid character varying, vinvdate date, vinvduedate date, vinvtypepay character varying, vinvtypepayref character varying, vinvidbefore character varying, vinvdesc character varying, vinvamt numeric, vinvvatrate numeric, vinvamtvat numeric, vinvwhtrate numeric, vinvamtwht numeric, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) IS '
ใช้สำหรับสร้าง Invoice ขึ้นมา 1 รายการโดยจะไปสร้าง thcap_invoice_action ให้อัตโนมัติ และสร้าง Debit Note ใน thcap_dncn และ thcap_dncn_action ให้อัตโนมัติ';


--
-- TOC entry 581 (class 1255 OID 68303)
-- Dependencies: 6 1714
-- Name: thcap_ins_invoice_action(character varying, character varying, smallint, character varying, timestamp without time zone, character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION thcap_ins_invoice_action(vinvactiontype character varying, invid character varying, vserialaction smallint, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

BEGIN

	-- ตรวจสอบว่าข้อมูลบางอย่างที่เป็น NULL ได้จะให้เป็น NULL หรือไม่ หรือถ้ารับค่าเป็น NULL แต่เป็น NULL ไม่ได้ต้องเป็น 0.00
	IF vDoerRemask = '' THEN
		vDoerRemask = NULL;
	END IF;


	-- ถ้า function นี้เป็น INSERT เพราะฉะนั้น ActionType เป็น 'I' และมี Serial เป็น 1
	IF vInvActionType = 'I' THEN
		vSerialAction = 1;
	END IF;


	-- คำสั่ง INSERT ใน function thcap_invoice_action
	INSERT INTO account.thcap_invoice_action(
			"invActionType",
			"invoiceID",
			"serialAction",
			"doerID",
			"doerStamp", 
			"doerRemask")
		VALUES (vInvActionType,
			INVID,
			vSerialAction,
			vDoerID,
			vDoerStamp,
			vDoerRemask
		);

	return TRUE;

END;$$;


ALTER FUNCTION account.thcap_ins_invoice_action(vinvactiontype character varying, invid character varying, vserialaction smallint, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) OWNER TO dev;

--
-- TOC entry 4017 (class 0 OID 0)
-- Dependencies: 581
-- Name: FUNCTION thcap_ins_invoice_action(vinvactiontype character varying, invid character varying, vserialaction smallint, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION thcap_ins_invoice_action(vinvactiontype character varying, invid character varying, vserialaction smallint, vdoerid character varying, vdoerstamp timestamp without time zone, vdoerremask character varying) IS '
ใช้สำหรับสร้าง thcap_invoice_action ตามข้อมูลที่ใส่ใน function';


--
-- TOC entry 575 (class 1255 OID 68152)
-- Dependencies: 1714 6
-- Name: thcap_mg_cancelReceipt(character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_mg_cancelReceipt"(recid character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

BEGIN

	-- ไป mask ใบเสร็จให้ status = 0 คือยกเลิกใบเสร็จ
	UPDATE account."thcap_receipt"
	SET "receiptStatus" = 0
	WHERE "receiptID" = recID;

	-- ไปลบผลของ ใบเสร็จตาม table ต่างๆ
	DELETE FROM account."thcap_mg_receipt_interest"
	WHERE "receiptID" = recID;

	DELETE FROM account."thcap_mg_receipt_payterm"
	WHERE "receiptID" = recID;
	
	DELETE FROM account."thcap_mg_statement"
	WHERE "receiptID" = recID;

	RETURN true;

END;$$;


ALTER FUNCTION account."thcap_mg_cancelReceipt"(recid character varying) OWNER TO dev;

--
-- TOC entry 584 (class 1255 OID 67534)
-- Dependencies: 1714 6
-- Name: thcap_mg_cancelReceiptTG(); Type: FUNCTION; Schema: account; Owner: postgres
--

CREATE FUNCTION "thcap_mg_cancelReceiptTG"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$DECLARE

invEffectedRow RECORD;

BEGIN

	/* ====================================================================================
	ตอนยกเลิกใบเสร็จ
	------------------------------------------------------
		เมื่อยกเลิกใบเสร็จ จะต้องเปลี่ยน receiptStatus = 0 ซึ่งจะเป็นการเรียก Trigger นี้อัตโนมัติ

	A. ไม่ว่าจะเป็นค่าใดๆ เนื่องจากการจ่ายจะจ่ายบนพื้นฐานใบแจ้งหนี้ จะต้องไปปรับใบแจ้งหนี้กลับ ให้ค่า invoiceAmtLeft
	หรือยอดคงเหลือ กลับไปเป็นค่าเดิมก่อนออกใบเสร็จ (เอายอดรายการที่ยกเลิกใบเสร็จไปเพิ่ม หนี้กลับมา)

	B. เฉพาะกรณีที่เป็นค่าผ่อน จะต้องไปเคลียร์รายการตาราง ตามด้านล่างหนี้ด้วย
		- account."thcap_mg_invoice_payterm" : ลบใบแจ้งหนี้ *

	C. เฉพาะกรณีที่เป็นค่าผ่อน จะต้องไปเคลียร์รายการตาราง ตามด้านล่างหนี้ด้วย
		- account."thcap_mg_receipt_interest" : ลบใบเสร็จ
		- account."thcap_mg_receipt_principle" : ลบใบเสร็จ
		- account."thcap_mg_statement" : ลบใบเสร็จ

	* หมายเหตุ - account."thcap_mg_invoice_payterm จะเคลียร์เฉพาะรายการที่เป็น INV ที่เป็นการจ่ายเกินจากปกติเท่านั้น
		INV หลักของงวดนั้นๆ ที่มีเลขอยู่ใน account."thcap_mg_payTerm" จะไม่ลบ

	 ==================================================================================== */



	/*  ====================================================================================
	A. เพิ่มหนี้กลับไปใน INV
	 ==================================================================================== */
	-- ดูว่าใบเสร็จที่ยกเลิกส่งผลต่อ INV ใบไหนบ้างที่ต้องไปบวกหนี้กลับ
	FOR invEffectedRow IN SELECT "rToInvoiceID", "rAmt" FROM account.thcap_receipt_details WHERE "receiptID" = OLD."receiptID" LOOP

	-- บวกกลับนี้เข้าไปจากของเดิมเผื่อบางกรณีจ่ายเหลือ Partial ไม่ใช่ 0 ทีเดียว
	UPDATE account.thcap_invoice
		SET "invoiceAmtLeft"="invoiceAmtLeft"+invEffectedRow."rAmt"
	WHERE "invoiceID" = invEffectedRow."rToInvoiceID";


	/*  ====================================================================================
	B. เฉพาะกรณีค่าผ่อน ไปลบผลของ ใบเสร็จตาม table ต่างๆ
	 ==================================================================================== */
	DELETE FROM account."thcap_mg_invoice_payterm"
	WHERE "invoiceID" = invEffectedRow."rToInvoiceID";

	END LOOP;


	/*  ====================================================================================
	C. เฉพาะกรณีค่าผ่อน ไปลบผลของ ใบเสร็จตาม table ต่างๆ
	 ==================================================================================== */
	DELETE FROM account."thcap_mg_receipt_interest"
	WHERE "receiptID" = OLD."receiptID";

	DELETE FROM account."thcap_mg_receipt_principle"
	WHERE "receiptID" = OLD."receiptID";
	
	DELETE FROM account."thcap_mg_statement"
	WHERE "receiptID" = OLD."receiptID";



	RETURN NULL;

END;$$;


ALTER FUNCTION account."thcap_mg_cancelReceiptTG"() OWNER TO postgres;

--
-- TOC entry 4018 (class 0 OID 0)
-- Dependencies: 584
-- Name: FUNCTION "thcap_mg_cancelReceiptTG"(); Type: COMMENT; Schema: account; Owner: postgres
--

COMMENT ON FUNCTION "thcap_mg_cancelReceiptTG"() IS 'เคลียร์ขยะที่เกิดขึ้นจากใบเสร็จนี้ให้เรียบร้อย หากถูก mask ว่าลบ';


--
-- TOC entry 578 (class 1255 OID 68153)
-- Dependencies: 6 1714
-- Name: thcap_mg_chkInvalidIntSumAmtPerSerial(character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_mg_chkInvalidIntSumAmtPerSerial"(conid character varying) RETURNS smallint
    LANGUAGE plpgsql
    AS $$DECLARE

	result integer;
	chkserial integer;

BEGIN

	result = 0;

	
	select into chkserial
		account."V_thcap_mg_intReceiptSum"."intSerial"
	from 
		account."V_thcap_mg_intReceiptSum"
	where 
		account."V_thcap_mg_intReceiptSum"."sumAmtPerSerial" > account."V_thcap_mg_intReceiptSum"."intAmtByCurRate" AND
		account."V_thcap_mg_intReceiptSum"."contractID" = conid;

	if chkserial is NULL then
		result = 1;
	end if;
	

	RETURN result;

END;$$;


ALTER FUNCTION account."thcap_mg_chkInvalidIntSumAmtPerSerial"(conid character varying) OWNER TO dev;

--
-- TOC entry 4020 (class 0 OID 0)
-- Dependencies: 578
-- Name: FUNCTION "thcap_mg_chkInvalidIntSumAmtPerSerial"(conid character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION "thcap_mg_chkInvalidIntSumAmtPerSerial"(conid character varying) IS 'ตรวจสอบว่า มีการจ่ายดอกเบี้ยเกินในช่วงหนึ่งๆ เกินค่าดอกเบี้ยที่ต้องจ่ายในช่วงๆหนึ่งหรือไม่

return 0 - มีการจ่ายเกิน
return 1 - ยังจ่ายไม่ครบ หรือจ่ายพอดี
';


--
-- TOC entry 576 (class 1255 OID 68154)
-- Dependencies: 1714 6
-- Name: thcap_mg_chkReceiptDetails(character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_mg_chkReceiptDetails"(recid character varying) RETURNS smallint
    LANGUAGE plpgsql
    AS $$DECLARE

	result smallint; -- ผลการตรวจสอบ
	recAmt numeric(15,2); -- จำนวนเงินทั้งหมดที่ถือว่าได้รับ (ไม่รวม VAT)
	recNetAmt numeric(15,2); -- จำนวนเงินเฉพาะที่ได้รับจริงของค่านั้นๆ (ไม่รวม VAT) = recAmt - recDiscount
	recDiscount numeric(15,2); -- ส่วนลดที่หักออกจากค่าที่ต้องเก็บ
	recTotalVAT numeric(15,2); -- ภาษีมูลค่าเพิ่มทั้งหมด = 7% x recNetAmt แต่ละรายการ
	recTotalWHT numeric(15,2); -- ภาษีหัก ณ ที่จ่ายทั้งหมด = x% x recNetAmt แต่ละรายการ
	recNetReceive numeric(15,2); -- จำนวนเงินที่รับจริง = recNetAmt + recTotalVAT
	recSumAmt numeric(15,2);
	recSumNetAmt numeric(15,2);
	recSumDiscount numeric(15,2);
	recSumAmtVAT numeric(15,2);
	recSumAmtWHT numeric(15,2);
	recSumNetReceive numeric(15,2);

BEGIN

	result = 1;

	-- Declare variables that retrieve from database
	select into recAmt, recNetAmt, recDiscount, recTotalVAT, recTotalWHT
		account."thcap_receipt"."receiveAmt",
		account."thcap_receipt"."receiveNetAmt",
		account."thcap_receipt"."receiveDiscount",
		account."thcap_receipt"."receiveTotalVAT",
		account."thcap_receipt"."receiveTotalWHT"
	from 
		account."thcap_receipt"
	where 
		account."thcap_receipt"."receiptID" = recID;

	recNetReceive = recNetAmt + recTotalVAT;


	-- Declare variables that retrieve from database
	select into recSumAmt, recSumNetAmt, recSumDiscount, recSumAmtVAT, recSumAmtWHT
		SUM(account."thcap_receipt_details"."rAmt"),
		SUM(account."thcap_receipt_details"."rNetAmt"),
		SUM(account."thcap_receipt_details"."rDiscount"),
		SUM(account."thcap_receipt_details"."rAmtVAT"),
		SUM(account."thcap_receipt_details"."rAmtWHT")
	from 
		account."thcap_receipt_details"
	where 
		account."thcap_receipt_details"."receiptID" = recID;

	recSumNetReceive = recSumNetAmt + recSumAmtVAT;


	-- Check processes. Even one of these is not pass -> result = 0;
	if recAmt != recSumAmt then
		result = 0;
	end if;

	if recNetAmt != recSumNetAmt then
		result = 0;
	end if;

	if recDiscount != recSumDiscount then
		result = 0;
	end if;

	if recTotalVAT != recSumAmtVAT then
		result = 0;
	end if;

	if recNetReceive != recSumNetReceive then
		result = 0;
	end if;

	if recAmt is NULL then
		result = 0;
	end if;

	
	RETURN result;

END;$$;


ALTER FUNCTION account."thcap_mg_chkReceiptDetails"(recid character varying) OWNER TO dev;

--
-- TOC entry 4021 (class 0 OID 0)
-- Dependencies: 576
-- Name: FUNCTION "thcap_mg_chkReceiptDetails"(recid character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION "thcap_mg_chkReceiptDetails"(recid character varying) IS 'ตรวจสอบว่า เงินรวมในใบเสร็จ กับ เงินแต่ละรายการ เมื่อรวมกันต้องเท่ากัน

return 0 - ไม่เท่ากัน
return 1 - เท่ากัน
';


--
-- TOC entry 579 (class 1255 OID 68155)
-- Dependencies: 6 1714
-- Name: thcap_mg_chkReceiptInterest(character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_mg_chkReceiptInterest"(recid character varying) RETURNS smallint
    LANGUAGE plpgsql
    AS $$DECLARE

	result smallint;
	recInt numeric(15,2);
	recSumInt numeric(15,2);

	recIntType varchar;

BEGIN

	result = 0;

	select into recIntType account."thcap_mg_getInterestType"();

	select into recInt
		account."thcap_receipt_details"."rAmt"
	from 
		account."thcap_receipt_details"
	where 
		account."thcap_receipt_details"."receiptID" = recID AND
		account."thcap_receipt_details"."rType" = recIntType;


	select into recSumInt
		SUM(account."thcap_mg_receipt_interest"."amtPerSerial")
	from 
		account."thcap_mg_receipt_interest"
	where 
		account."thcap_mg_receipt_interest"."receiptID" = recID;

	if recInt = recSumInt then
		result = 1;
	end if;

	RETURN result;

END;$$;


ALTER FUNCTION account."thcap_mg_chkReceiptInterest"(recid character varying) OWNER TO dev;

--
-- TOC entry 4022 (class 0 OID 0)
-- Dependencies: 579
-- Name: FUNCTION "thcap_mg_chkReceiptInterest"(recid character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION "thcap_mg_chkReceiptInterest"(recid character varying) IS 'ตรวจสอบว่า เงินในใบเสร็จนั้นๆที่จ่ายดอกเบี้ย กับ เงินแต่ที่จ่ายดอกเบี้ยของใบเสร็จนั้นๆแตกไปแต่ละช่วงดอกเบี้ย เมื่อรวมกันต้องเท่ากัน

return 0 - ไม่เท่ากัน
return 1 - เท่ากัน
';


--
-- TOC entry 573 (class 1255 OID 68156)
-- Dependencies: 6 1714
-- Name: thcap_mg_chkReceiptPT(character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_mg_chkReceiptPT"(recid character varying) RETURNS smallint
    LANGUAGE plpgsql
    AS $$DECLARE

	result smallint;
	recPT numeric(15,2);
	recSumPT numeric(15,2);

	recIntType varchar;
	recPrincipleType varchar;

BEGIN

	result = 0;

	select into recIntType account."thcap_mg_getInterestType"();
	select into recPrincipleType account."thcap_mg_getPrincipleType"();

	select into recPT
		sum(account."thcap_receipt_details"."rAmt")
	from 
		account."thcap_receipt_details"
	where 
		account."thcap_receipt_details"."receiptID" = recID AND
		( account."thcap_receipt_details"."rType" = recPrincipleType OR
		account."thcap_receipt_details"."rType" = recIntType );


	select into recSumPT
		SUM(account."thcap_mg_receipt_payterm"."amtToThisPTNum")
	from 
		account."thcap_mg_receipt_payterm"
	where 
		account."thcap_mg_receipt_payterm"."receiptID" = recID;

	if recPT = recSumPT then
		result = 1;
	end if;

	RETURN result;

END;$$;


ALTER FUNCTION account."thcap_mg_chkReceiptPT"(recid character varying) OWNER TO dev;

--
-- TOC entry 4023 (class 0 OID 0)
-- Dependencies: 573
-- Name: FUNCTION "thcap_mg_chkReceiptPT"(recid character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION "thcap_mg_chkReceiptPT"(recid character varying) IS 'ตรวจสอบว่า เงินในใบเสร็จนั้นๆที่จ่ายดอกเบี้ยและเงินต้น (ยอดผ่อน) กับ เงินที่ถูกนำไปแยกจ่ายในแต่ละ Due เมื่อรวมกันจะต้องเท่ากัน

return 0 - ไม่เท่ากัน
return 1 - เท่ากัน
';


--
-- TOC entry 580 (class 1255 OID 68157)
-- Dependencies: 6 1714
-- Name: thcap_mg_getIntOnDate(character varying, date, smallint); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_mg_getIntOnDate"(vidno character varying, focusdate date, inttype smallint DEFAULT 1) RETURNS numeric
    LANGUAGE plpgsql
    AS $$DECLARE

	interest_type_000 numeric(30,15); -- ไม่ปัดเศษ
	interest_type_001 numeric(15,2); -- ปัดเศษเหลือ 2 ตำแหน่ง โดยใช้หลักการปัดมาตราฐาน

BEGIN

	if inttype = 1 then

		select into interest_type_001
			account."thcap_mg_interest"."intAmtPerDayRounded"
		from 
			account."thcap_mg_interest"
		where 
			account."thcap_mg_interest"."contractID" = vidno AND
			account."thcap_mg_interest"."intStartDate" <= focusDate AND
			account."thcap_mg_interest"."intEndDate" >= focusDate;

		RETURN interest_type_001;

	elsif inttype = 0 then

		select into interest_type_000
			account."thcap_mg_interest"."intAmtPerDay"
		from 
			account."thcap_mg_interest"
		where 
			account."thcap_mg_interest"."contractID" = vidno AND
			account."thcap_mg_interest"."intStartDate" <= focusDate AND
			account."thcap_mg_interest"."intEndDate" >= focusDate;

		RETURN interest_type_000;
	end if;

END;$$;


ALTER FUNCTION account."thcap_mg_getIntOnDate"(vidno character varying, focusdate date, inttype smallint) OWNER TO dev;

--
-- TOC entry 4024 (class 0 OID 0)
-- Dependencies: 580
-- Name: FUNCTION "thcap_mg_getIntOnDate"(vidno character varying, focusdate date, inttype smallint); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION "thcap_mg_getIntOnDate"(vidno character varying, focusdate date, inttype smallint) IS 'ขอเงินดอกเบี้ยดอกเบี้ยต่อ 1 วัน 

0: ในรูปแบบที่ยังไม่ปัด
1: รูปแบบที่ปัดแล้ว';


--
-- TOC entry 459 (class 1255 OID 68306)
-- Dependencies: 6 1714
-- Name: thcap_mg_getInterestType(); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_mg_getInterestType"() RETURNS character varying
    LANGUAGE plpgsql
    AS $$DECLARE

BEGIN

	RETURN '1002';

END;$$;


ALTER FUNCTION account."thcap_mg_getInterestType"() OWNER TO dev;

--
-- TOC entry 455 (class 1255 OID 69271)
-- Dependencies: 6 1714
-- Name: thcap_mg_getMinPayType(); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_mg_getMinPayType"() RETURNS character varying
    LANGUAGE plpgsql
    AS $$DECLARE

BEGIN

	RETURN '1000';

END;$$;


ALTER FUNCTION account."thcap_mg_getMinPayType"() OWNER TO dev;

--
-- TOC entry 490 (class 1255 OID 68307)
-- Dependencies: 1714 6
-- Name: thcap_mg_getPrincipleType(); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_mg_getPrincipleType"() RETURNS character varying
    LANGUAGE plpgsql
    AS $$DECLARE

BEGIN

	RETURN '1001';

END;$$;


ALTER FUNCTION account."thcap_mg_getPrincipleType"() OWNER TO dev;

--
-- TOC entry 583 (class 1255 OID 69697)
-- Dependencies: 1714 6
-- Name: thcap_mg_receiptPayToInv(); Type: FUNCTION; Schema: account; Owner: postgres
--

CREATE FUNCTION "thcap_mg_receiptPayToInv"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$DECLARE

vReceiptStatus smallint;

BEGIN

	/* ====================================================================================
	ตอนรับชำระใบเสร็จตามรายการ Invoice ใน account.thcap_receipt_details
	------------------------------------------------------
		เมื่อมีการรับชำระใบเสร็จเข้ารายการ INV ใด จะต้องไป Update ค่า invoiceAmtLeftให้ลดลงตามจำนวนเงินที่จ่าย
	และถ้าจ่ายไม่เหลือ invoiceAmtLeft = 0.00 แสดงว่าจ่ายบางส่วน

	==================================================================================== */

	/* !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! เริ่มยกเลิก !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	-- ประกาศค่าตัวแปร
	vReceiptStatus = 0;

	-- ตรวจสอบดูก่อนว่าใบเสร็จที่เกิดใน thcap_receipt สถานะเป็นอนุมัติหรือยัง
	SELECT INTO vReceiptStatus
		"receiptStatus"
	FROM
		account.thcap_receipt
	WHERE "receiptID" = NEW."receiptID";
	!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! สิ้นสุดยกเลิก !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! */

	
	-- ตรวจสอบว่าใบเสร็จที่ถูกเพิ่มเข้าไป เป็น ACTIVE หรือ WAIT ถ้า ACTIVE ก็ UPDATE receipt_details เลย
	--IF vReceiptStatus = 1 THEN
		-- ดูว่าใบเสร็จที่ถูกเพิ่ม (INSERT) ใน account.thcap_receipt_details (และอนุมัติแล้ว) ไปจ่าย Invoice เลขอะไรเท่าไหร่บ้าง เป็น ROWๆ ที่ INSERT
		UPDATE account.thcap_invoice
			-- นำเงินที่จ่ายมาไปลบออก
			SET "invoiceAmtLeft"="invoiceAmtLeft"-NEW."rAmt"
		WHERE "invoiceID" = NEW."rToInvoiceID";
	--END IF;



	RETURN NULL;

END;$$;


ALTER FUNCTION account."thcap_mg_receiptPayToInv"() OWNER TO postgres;

--
-- TOC entry 4025 (class 0 OID 0)
-- Dependencies: 583
-- Name: FUNCTION "thcap_mg_receiptPayToInv"(); Type: COMMENT; Schema: account; Owner: postgres
--

COMMENT ON FUNCTION "thcap_mg_receiptPayToInv"() IS 'ปรับปรุง invoiceAmtLeft ใน account.thcap_invoice เมื่อมีรับชำระ';


--
-- TOC entry 582 (class 1255 OID 69695)
-- Dependencies: 6 1714
-- Name: thcap_mg_recreateMainInvPT(); Type: FUNCTION; Schema: account; Owner: postgres
--

CREATE FUNCTION "thcap_mg_recreateMainInvPT"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$DECLARE

isMainInv smallint;

BEGIN

	/* ====================================================================================
	ตอน Main Invoice ถูกลบ
	------------------------------------------------------
		Main Invoice คือหนี้หลักที่ต้องจ่ายของงวดผ่อนนั้นๆ ซึ่งอาจจะถูกลบจากบางโอกาส จะต้องใส่กลับเข้าไปเพื่อไม่ให้เกิดปัญหา

	 ==================================================================================== */

	-- ประกาศตัวแปร
	isMainInv = 0;

	-- CHECK ว่าเป็น MAIN INVOICE หรือไม่
	SELECT INTO isMainInv 
		COUNT("ptInvID")
	FROM 
		account."thcap_mg_payTerm"
	WHERE 
		"ptInvID" = OLD."invoiceID";

	IF isMainInv > 0 THEN
		-- INSERT MAIN INVOICE กลับเข้าไป
		INSERT INTO account.thcap_mg_invoice_payterm(
			    "invoiceID",
			    "ptNum")
		    VALUES (OLD."invoiceID", OLD."ptNum");
	END IF;


	RETURN NULL;

END;$$;


ALTER FUNCTION account."thcap_mg_recreateMainInvPT"() OWNER TO postgres;

--
-- TOC entry 4027 (class 0 OID 0)
-- Dependencies: 582
-- Name: FUNCTION "thcap_mg_recreateMainInvPT"(); Type: COMMENT; Schema: account; Owner: postgres
--

COMMENT ON FUNCTION "thcap_mg_recreateMainInvPT"() IS 'สร้างรายการ Invoice ที่เป็น Main Pay Term กลับมาใหม่หากโดนลบ';


--
-- TOC entry 480 (class 1255 OID 68522)
-- Dependencies: 1714 6
-- Name: thcap_set_nextRunningID(character varying, character varying); Type: FUNCTION; Schema: account; Owner: dev
--

CREATE FUNCTION "thcap_set_nextRunningID"(vcompid character varying, vfieldname character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

BEGIN

	UPDATE 
		public.thcap_running_number
	SET 
		"runningNum"= "runningNum" + 1
	WHERE 
		"compID" = vCompID AND
		"fieldName" = vFieldName;

	RETURN TRUE;
		

END;$$;


ALTER FUNCTION account."thcap_set_nextRunningID"(vcompid character varying, vfieldname character varying) OWNER TO dev;

--
-- TOC entry 4029 (class 0 OID 0)
-- Dependencies: 480
-- Name: FUNCTION "thcap_set_nextRunningID"(vcompid character varying, vfieldname character varying); Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON FUNCTION "thcap_set_nextRunningID"(vcompid character varying, vfieldname character varying) IS '
คำอธิบาย:
---------------------------
function สำหรับกำหนดค่าเลขที่ running ID ว่างเลขถัดไป 1 เลข

ใส่ค่า:
---------------------------
ชื่อบริษัทที่ต้องการ,
รหัส field ที่ต้องการ,

เช่น
SELECT account."thcap_set_nextRunningID"(
    ''THCAP'',
    ''revTranID'',
);


ค่า Return:
---------------------------
ไม่มี';


SET search_path = carregis, pg_catalog;

--
-- TOC entry 481 (class 1255 OID 64395)
-- Dependencies: 7 1714
-- Name: CreateCarInstalls(date, double precision[]); Type: FUNCTION; Schema: carregis; Owner: dev
--

CREATE FUNCTION "CreateCarInstalls"(postdate date, smeter double precision[]) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temmrr "Fc"%ROWTYPE;
	carid varchar(25);
	caryear integer;
	expdate date;
	idno varchar(25);
	mtt text;
	tdd date;
	accclose text;
	cusid text;
	yy integer;
	mm integer;
	dyy integer;
	dmm integer;
	fyear integer;
	num integer;
	ctdate date;
	truefalse integer;
	duedate date;
	temgid varchar(25);
	beg integer;
	gdd integer;
	gmm integer;
	gyy integer;
	gyy_ms date;
	gyy_ms_ms integer;
	gyy_ms_lob integer;
	gmm_ms integer;
BEGIN
	beg := 0;
	yy:=EXTRACT(YEAR FROM postdate); --หา ปี ปัจจุบัน
	mm:=EXTRACT(MONTH FROM postdate); --หา เดือน ปัจจุบัน
	FOR temmrr IN SELECT * FROM public."Fc" Where "Fc"."C_StartDate" is not null ORDER BY "Fc"."C_REGIS" ASC LOOP
	   carid := temmrr."CarID";
	   caryear := temmrr."C_YEAR";
	   expdate := temmrr."C_StartDate";
	   
	   fyear:=yy-caryear; -- หาจำนวนปีของรถ
	   if fyear < 8 then
	      num := 6;
	   else
	      num := 4;
	   end if;

	   select into cusid "VContact"."CusID" from public."VContact" Where ("VContact"."asset_id" = carid);
	   select into idno "VContact"."IDNO" from public."VContact" Where ("VContact"."asset_id" = carid);
	   if idno is not null then

	   select MAX("CarTaxDue"."TaxDueDate") into tdd from carregis."CarTaxDue" Where ("CarTaxDue"."IDNO" = idno);
	   select into mtt "CarTaxDue"."TypeDep" from carregis."CarTaxDue" Where ("CarTaxDue"."IDNO" = idno AND "CarTaxDue"."TaxDueDate" = tdd);

	   if tdd isnull then
	      gmm:=EXTRACT(MONTH FROM expdate);
	      gdd:=EXTRACT(DAY FROM expdate);
	      gyy:=EXTRACT(YEAR FROM expdate);
	      gyy_ms:=current_date;
	      gmm_ms:=EXTRACT(MONTH FROM gyy_ms);
	      gyy_ms_ms:=EXTRACT(YEAR FROM gyy_ms);
	      gyy_ms_lob:=gyy_ms_ms-1;

	      if num = 6 then
		if gyy_ms_lob = gyy then
		   if gmm >= 7 then
		      ctdate:= gyy || '-' || gmm || '-' || gdd;
		   else
		      ctdate:= gyy_ms_ms || '-' || gmm || '-' || gdd;
		   end if;
		else
		   if gmm >= gmm_ms then
		      ctdate:= gyy_ms_lob || '-' || gmm || '-' || gdd;
		   else
		      ctdate:= gyy_ms_ms || '-' || gmm || '-' || gdd;
		   end if;
		end if;
	      elseif num = 4 then
		if gyy_ms_lob = gyy then
		   if gmm >= 9 then
		      ctdate:= gyy || '-' || gmm || '-' || gdd;
		   else
		      ctdate:= gyy_ms_ms || '-' || gmm || '-' || gdd;
		   end if;
		else
		   if gmm >= gmm_ms then
		      ctdate:= gyy_ms_lob || '-' || gmm || '-' || gdd;
		   else
		      ctdate:= gyy_ms_ms || '-' || gmm || '-' || gdd;
		   end if;
		end if;
	      end if;
	      
	   else
	      ctdate:= tdd;
	   end if;

	   select into accclose "Fp"."P_ACCLOSE" from "Fp" Where ("Fp"."IDNO" = idno AND "Fp"."CusID" = cusid);

	   if mtt isnull then
	      truefalse:='105';
	   else
	      if mtt = '101' then
	         truefalse:='105';
	      else
	         truefalse:='101';
	      end if;
	   end if;

	   duedate:=ctdate + (num||' month')::INTERVAL;
	   dyy:=EXTRACT(YEAR FROM duedate); --หา ปี ปัจจุบัน
	   dmm:=EXTRACT(MONTH FROM duedate); --หา เดือน ปัจจุบัน
	   if yy = dyy AND mm = dmm AND accclose = 'f' then
	      beg := beg + 1;
	      temgid := carregis.gen_id(postdate); -- GenID
	      INSERT INTO carregis."CarTaxDue"("IDCarTax", "IDNO", "TaxDueDate", "TypeDep", "CusAmt") 
	         values(temgid,idno,duedate,truefalse,smeter[beg]);
	   end if;

	   end if;
	   
	END LOOP;

	RETURN true;

END;$$;


ALTER FUNCTION carregis."CreateCarInstalls"(postdate date, smeter double precision[]) OWNER TO dev;

--
-- TOC entry 482 (class 1255 OID 64396)
-- Dependencies: 1714 7
-- Name: gen_id(date); Type: FUNCTION; Schema: carregis; Owner: dev
--

CREATE FUNCTION gen_id(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	mm integer;
	yy integer;
	
	temcarid integer;
	temid text:='';
	temdate text := '';
BEGIN

	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	select into temcarid carregis."CarID"."carid"
	from carregis."CarID" Where (extract(month from carregis."CarID"."monthid")=mm and extract(year from carregis."CarID"."monthid")=yy);

	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(1,'99');
		insert into carregis."CarID" values (to_date(temdate,'YYYY-MM-DD') ,0);
		temcarid:=0;
	end if;

	update carregis."CarID" set  "carid"=temcarid+1 Where (extract(month from carregis."CarID"."monthid")=mm and extract(year from carregis."CarID"."monthid")=yy); 
	temid :='CR' || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(temcarid+1,'FM0009');
	
	RETURN temid;
END;
$$;


ALTER FUNCTION carregis.gen_id(datein date) OWNER TO dev;

--
-- TOC entry 483 (class 1255 OID 64397)
-- Dependencies: 1714 7
-- Name: outstanding_registype(text); Type: FUNCTION; Schema: carregis; Owner: dev
--

CREATE FUNCTION outstanding_registype(crgid text) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
	amt double precision;
	outstand double precision:=0.00;
	payready double precision:=0.00;

BEGIN
	select carregis."CarTaxDue"."CusAmt" into outstand  from carregis."CarTaxDue" where carregis."CarTaxDue"."IDCarTax"=crgid;
	select sum("FOtherpay"."O_MONEY") into payready  from "FOtherpay" where "FOtherpay"."RefAnyID"=crgid and "FOtherpay"."Cancel"=false;
	if payready is null 
		then payready:=0.00;
	end if;
	amt := outstand-payready;
	return amt;
END;$$;


ALTER FUNCTION carregis.outstanding_registype(crgid text) OWNER TO dev;

SET search_path = corporate, pg_catalog;

--
-- TOC entry 484 (class 1255 OID 64398)
-- Dependencies: 8 1714
-- Name: chek_outstanding(text); Type: FUNCTION; Schema: corporate; Owner: dev
--

CREATE FUNCTION chek_outstanding(idno text, OUT last_due date, OUT remain double precision, OUT min_amt double precision, OUT due_amt double precision) RETURNS record
    LANGUAGE plpgsql
    AS $$DECLARE

	tem_remain double precision;
	tem_min_amt double precision;
	tem_due_amt double precision;
	
BEGIN
	select into last_due  corporate."VCorpDetail"."DueDate" from corporate."VCorpDetail" where "IDNO"=idno and "O_RECEIPT" is null order by "DueDate" desc;
	select sum(corporate."VCorpDetail"."amt")  into tem_remain from corporate."VCorpDetail" where "IDNO"=idno and "O_RECEIPT" is null;
	select into tem_min_amt corporate."VCorpDetail"."amt" from corporate."VCorpDetail" where "IDNO"=idno and "O_RECEIPT" is null;
	select into tem_due_amt corporate."VCorpContact"."amt" from corporate."VCorpContact" where "IDNO" = idno ;
	
	if tem_remain is null then remain:=0;  else remain:=tem_remain; end if;
	if tem_min_amt is null then min_amt:=0;  else min_amt:=tem_min_amt; end if;
	if tem_due_amt is null then due_amt:=0;  else due_amt:=tem_due_amt; end if;
	
END;
$$;


ALTER FUNCTION corporate.chek_outstanding(idno text, OUT last_due date, OUT remain double precision, OUT min_amt double precision, OUT due_amt double precision) OWNER TO dev;

--
-- TOC entry 485 (class 1255 OID 64399)
-- Dependencies: 1714 8
-- Name: gen_inv_no(date); Type: FUNCTION; Schema: corporate; Owner: dev
--

CREATE FUNCTION gen_inv_no(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	mm integer;
	yy integer;
	
	temv integer;
	temdate text:='';
	outputmsg text:='';
BEGIN
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);
		
	select into temv  r."I" from corporate."CReceiptNO" r Where r.i_month = mm  and r.i_year=yy;

	if not found  then	
		insert into corporate."CReceiptNO" values (yy,mm,0);
		temv:=0;
	end if;
	
	update corporate."CReceiptNO" set  "I"="I"+1 
	Where  corporate."CReceiptNO"."i_month"=mm  and  corporate."CReceiptNO"."i_year"=yy ;
	outputmsg :='IV'|| substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09')  || to_char(temv+1,'FM00009');
	RETURN outputmsg;
END;
$$;


ALTER FUNCTION corporate.gen_inv_no(datein date) OWNER TO dev;

--
-- TOC entry 486 (class 1255 OID 64400)
-- Dependencies: 8 1714
-- Name: gen_invoice_allmonth(date); Type: FUNCTION; Schema: corporate; Owner: dev
--

CREATE FUNCTION gen_invoice_allmonth(datein date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temcpcontact corporate."VCorpContact"%ROWTYPE;
	invdate date;
	duedate date;
	temdate  text;
	mm integer;
	yy integer;
BEGIN
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);
	
	temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-01' ;
	invdate := to_date(temdate,'YYYY-MM-DD');
	
	for temcpcontact in select * from corporate."VCorpContact" c where c."AcClose"=false loop
		select into duedate i."DueDate" from corporate.corpinvoice i
		where  i."DueDate" = invdate and i."IDNO" = temcpcontact."IDNO";
		if not found then
			insert into corporate.corpinvoice 
			values (corporate.gen_inv_no(datein),temcpcontact."IDNO",invdate,temcpcontact."amt",current_date,false);
		end if;
	end loop;
	return true;
END;
$$;


ALTER FUNCTION corporate.gen_invoice_allmonth(datein date) OWNER TO dev;

--
-- TOC entry 487 (class 1255 OID 64401)
-- Dependencies: 8 1714
-- Name: gen_invoice_new_cus(text); Type: FUNCTION; Schema: corporate; Owner: dev
--

CREATE FUNCTION gen_invoice_new_cus(idno text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	dd integer;
	mm integer;
	yy integer;
	
	startdate date;
	signdate  date;
	duedate date;
	temamt double precision;
	temsignamt double precision;
	
BEGIN

	dd:=EXTRACT(DAY FROM current_date);
	mm:=EXTRACT(MONTH FROM current_date);
	yy:=EXTRACT(YEAR FROM current_date);
	
	select into startdate, signdate ,temamt,temsignamt  corporate."VCorpContact"."ACStartDate", corporate."VCorpContact"."SignDate" ,corporate."VCorpContact".amt,corporate."VCorpContact".sign_amt
	from corporate."VCorpContact" Where (corporate."VCorpContact"."IDNO" = idno);

	if temsignamt <> 0 then
		insert into corporate.corpinvoice 
		values (corporate.gen_inv_no(mm,yy),idno,signdate,temsignamt,current_date,false,null);
	end if;

	duedate = startdate;
		
	while   duedate <= current_date    
	loop
		insert into corporate.corpinvoice 
		values (corporate.gen_inv_no(mm,yy),idno,duedate,amt,current_date,false,null);
		duedate:=duedate+ (1||'month')::interval;		
	end loop;
END;
$$;


ALTER FUNCTION corporate.gen_invoice_new_cus(idno text) OWNER TO dev;

--
-- TOC entry 488 (class 1255 OID 64402)
-- Dependencies: 1714 8
-- Name: update_inv(text, double precision, text); Type: FUNCTION; Schema: corporate; Owner: dev
--

CREATE FUNCTION update_inv(idno text, amt double precision, recno text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temamt double precision;
	amt_inv double precision;
	tem_inv text;	
BEGIN
	temamt := amt;
	while temamt >= 0 loop
		select into tem_inv,amt_inv  inv_no,amt from corporate.corpinvoice where "IDNO" = idno and "Cancel" = false and "RefReceipt" is null order by "DueDate" ;
		update corporate.corpinvoice set "RefReceipt" = recno where inv_no = tem_inv;
		temamt:=temamt-amt_inv;
	end loop;
	return true;	
END;
$$;


ALTER FUNCTION corporate.update_inv(idno text, amt double precision, recno text) OWNER TO dev;

SET search_path = gas, pg_catalog;

--
-- TOC entry 489 (class 1255 OID 64403)
-- Dependencies: 9 1714
-- Name: gen_id(date, integer, integer); Type: FUNCTION; Schema: gas; Owner: dev
--

CREATE FUNCTION gen_id(datein date, brnid integer, knd integer) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	mm integer;
	yy integer;
	tempoid integer;
	tempayid integer;
	temid text:='';
	temdate text := '';
BEGIN

-- parameter with sent in function
-- datein  use month and year which key gas in system
-- brnid is branchid 1=jaran, 2=tivanon
-- knd is kind 1=poid , 2=payid 
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);
	select into tempoid, tempayid gas."GasID"."poid", gas."GasID"."payid"
	from gas."GasID" Where (extract(month from gas."GasID"."monthid")=mm and extract(year from gas."GasID"."monthid")=yy and gas."GasID"."branch"=brnid);
	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(1,'99');
		insert into gas."GasID" values (1,to_date(temdate,'YYYY-MM-DD') ,0,0);
		tempoid:=0;
		tempayid:=0;
	end if;
	if knd=1 then
		update gas."GasID" set "poid"=tempoid+1 Where (extract(month from gas."GasID"."monthid")=mm and extract(year from gas."GasID"."monthid")=yy and gas."GasID"."branch"=brnid); 
		temid :='PO' || to_char(brnid,'FM99')  || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(tempoid+1,'FM009');
	elsif knd=2 then
		update gas."GasID" set "payid"=tempayid+1 Where (extract(month from gas."GasID"."monthid")=mm and extract(year from gas."GasID"."monthid")=yy and gas."GasID"."branch"=brnid); 
		temid :='PY' || to_char(brnid,'FM99')  || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(tempayid+1,'FM009');
	
	end if;


	RETURN temid;
END;
$$;


ALTER FUNCTION gas.gen_id(datein date, brnid integer, knd integer) OWNER TO dev;

SET search_path = insure, pg_catalog;

--
-- TOC entry 491 (class 1255 OID 64404)
-- Dependencies: 10 1714
-- Name: cal_comm(text, text, double precision); Type: FUNCTION; Schema: insure; Owner: dev
--

CREATE FUNCTION cal_comm(code text, insname text, netpm double precision) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
       ratecomm double precision;
       commamt double precision;
       userpercent boolean;
BEGIN
	select into ratecomm, userpercent  insure."Commision"."Commision", insure."Commision"."UsePercent"
	from 	insure."Commision" Where insure."Commision"."InsCompany"=insname and insure."Commision"."CommCode"=code;
	if userpercent then
	    commamt := netpm*ratecomm/100;
    else 
        commamt := ratecomm; 
	end if;
	return commamt;
END;
$$;


ALTER FUNCTION insure.cal_comm(code text, insname text, netpm double precision) OWNER TO dev;

--
-- TOC entry 492 (class 1255 OID 64405)
-- Dependencies: 1714 10
-- Name: cal_rate_insforce(text, date, date); Type: FUNCTION; Schema: insure; Owner: dev
--

CREATE FUNCTION cal_rate_insforce(code text, stdate date, endate date, OUT netpm double precision, OUT stamp integer, OUT vat double precision) RETURNS record
    LANGUAGE plpgsql
    AS $$DECLARE
	numday integer;
	tempm double precision;
BEGIN
	if endate > stdate then
		select into netpm,stamp,vat insure."RateInsForce"."IFNetPremium",insure."RateInsForce"."IFStamp",insure."RateInsForce"."IFTAX"
		from insure."RateInsForce" where insure."RateInsForce"."IFCode"=code;
		if (stdate+interval '1 year')=endate then
			netpm:=round(cast(netpm as numeric),2);
			vat:=round(cast(vat as numeric),2);
		else
			numday := endate-stdate+1;
			netpm:=round(cast(netpm/365*numday as numeric) ,2);
			stamp:=ceil(cast(netpm*0.4/100 as numeric));
			tempm:=netpm+stamp;
			vat:=round(cast(tempm*7/100 as numeric),2);
		end if;
	end if ;
END;$$;


ALTER FUNCTION insure.cal_rate_insforce(code text, stdate date, endate date, OUT netpm double precision, OUT stamp integer, OUT vat double precision) OWNER TO dev;

--
-- TOC entry 493 (class 1255 OID 64406)
-- Dependencies: 10 1714
-- Name: gen_co_insid(date, integer, integer); Type: FUNCTION; Schema: insure; Owner: dev
--

CREATE FUNCTION gen_co_insid(datein date, brnid integer, knd integer) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	mm integer;
	yy integer;
	temforceid integer;
	temunforceid integer;
	tempayid integer;
	temid text:='';
	temdate text := '';
BEGIN
-- parameter with sent in function
-- datein  use month and year which key insure in system
-- brnid is branchid 1=jaran, 2=tivanon
-- knd is kind of insure or payingtoinsure 1=force , 2=unforce, 3=pay to insure 
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);
	select into temforceid, temunforceid, tempayid   insure."InsureID"."forceid", insure."InsureID"."unforceid", insure."InsureID"."payid"
	from 	insure."InsureID" Where (extract(month from insure."InsureID"."monthid")=mm and extract(year from insure."InsureID"."monthid")=yy and insure."InsureID"."branch"=brnid);
	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(1,'99');
		insert into insure."InsureID" values (1,to_date(temdate,'YYYY-MM-DD') ,0,0,0);
		temforceid:=0;
		temunforceid:=0;
		tempayid:=0;
	end if;
	if knd=1 then
		update "insure"."InsureID" set  "forceid"=temforceid+1 Where (extract(month from insure."InsureID"."monthid")=mm and extract(year from insure."InsureID"."monthid")=yy and insure."InsureID"."branch"=brnid); 
		temid :='F' || to_char(brnid,'FM99')  || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(temforceid+1,'FM009');
	elsif knd=2 then
		update "insure"."InsureID" set  "unforceid"=temunforceid+1 Where (extract(month from insure."InsureID"."monthid")=mm and extract(year from insure."InsureID"."monthid")=yy and insure."InsureID"."branch"=brnid); 
		temid :='U' || to_char(brnid,'FM99')  || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(temunforceid+1,'FM009');
	elsif knd=3 then
		update "insure"."InsureID" set  "payid"=tempayid+1 Where (extract(month from insure."InsureID"."monthid")=mm and extract(year from insure."InsureID"."monthid")=yy and insure."InsureID"."branch"=brnid); 
		temid :='P' || to_char(brnid,'FM99')  || substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(tempayid+1,'FM009');
	end if;
	RETURN temid;
END;$$;


ALTER FUNCTION insure.gen_co_insid(datein date, brnid integer, knd integer) OWNER TO dev;

--
-- TOC entry 494 (class 1255 OID 64407)
-- Dependencies: 1714 10
-- Name: outstanding_insforce(text); Type: FUNCTION; Schema: insure; Owner: dev
--

CREATE FUNCTION outstanding_insforce(insid text) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
	amt double precision;
	outstand double precision:=0.00;
	payready double precision:=0.00;

BEGIN
	select insure."InsureForce"."CollectCus" into outstand  from insure."InsureForce" where insure."InsureForce"."InsFIDNO"=insid;
	select sum("FOtherpay"."O_MONEY") into payready  from "FOtherpay" where "FOtherpay"."RefAnyID"=insid and "FOtherpay"."Cancel"=false;
	if payready is null 
		then payready:=0.00;
	end if;
	amt := outstand-payready;
	return amt;
END;$$;


ALTER FUNCTION insure.outstanding_insforce(insid text) OWNER TO dev;

--
-- TOC entry 495 (class 1255 OID 64408)
-- Dependencies: 1714 10
-- Name: outstanding_insureunforce(text); Type: FUNCTION; Schema: insure; Owner: dev
--

CREATE FUNCTION outstanding_insureunforce(insid text) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
	amt double precision;
	outstand double precision:=0.00; --ราคาประกัน
	payready double precision:=0.00; --เงินรวมทุกใบเสร็จที่จ่ายมาแล้ว

BEGIN
	select insure."InsureUnforce"."CollectCus" into outstand  from insure."InsureUnforce" where insure."InsureUnforce"."InsUFIDNO"=insid;
	
	select sum("FOtherpay"."O_MONEY") into payready  from "FOtherpay" where "FOtherpay"."RefAnyID"=insid and "FOtherpay"."Cancel"=false;

	if payready is null 
		then payready:=0.00;
	end if;
	amt := outstand-payready;
	return amt;
END;
$$;


ALTER FUNCTION insure.outstanding_insureunforce(insid text) OWNER TO dev;

SET search_path = letter, pg_catalog;

--
-- TOC entry 496 (class 1255 OID 64409)
-- Dependencies: 11 1714
-- Name: gen_cusletid(text); Type: FUNCTION; Schema: letter; Owner: dev
--

CREATE FUNCTION gen_cusletid(idno text) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	temid text;
	tempos integer;
BEGIN
	select count(*) into tempos from letter.send_address where "IDNO" = idno;
	temid:=idno || to_char(tempos+1,'FM09');

	RETURN temid;
END;$$;


ALTER FUNCTION letter.gen_cusletid(idno text) OWNER TO dev;

--
-- TOC entry 497 (class 1255 OID 64410)
-- Dependencies: 1714 11
-- Name: gen_sendid(date); Type: FUNCTION; Schema: letter; Owner: dev
--

CREATE FUNCTION gen_sendid(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	dd integer;
	mm integer;
	yy integer;
	temid text;
	tempos integer;
	temdate text;
BEGIN
	dd:=EXTRACT(DAY FROM datein);
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);
	select into tempos  letter.send_no.s_no
	from letter.send_no Where (extract(month from letter.send_no.send_date)=mm and extract(year from letter.send_no.send_date)=yy and extract(day from letter.send_no.send_date)=dd);


	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
		insert into letter.send_no values (to_date(temdate,'YYYY-MM-DD') ,0);
		tempos:=0;
	end if;
	update letter.send_no set  s_no=s_no+1 Where (extract(month from letter.send_no.send_date)=mm and extract(year from letter.send_no.send_date)=yy and extract(day from letter.send_no.send_date)=dd);
	temid :='SL'||substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(tempos+1,'FM009');
	RETURN temid;
END;$$;


ALTER FUNCTION letter.gen_sendid(datein date) OWNER TO dev;

SET search_path = public, pg_catalog;

--
-- TOC entry 498 (class 1255 OID 64411)
-- Dependencies: 1714 15
-- Name: CalAmtDelay(date, date, double precision); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CalAmtDelay"(paydate date, duedate date, payment double precision) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
	temmrr "MRR"%ROWTYPE;
	temdate date;
	rateuse double precision;
	amtdelay double precision;
	outoftablemrr boolean;
	countdelayday integer;
	dayexcept integer := 7;
BEGIN
	amtdelay :=0.00;
	temdate := duedate;
	outoftablemrr := true;
	countdelayday := paydate-duedate;
	if paydate-duedate > dayexcept then
		FOR temmrr IN SELECT "MRR"."DateInput","MRR"."MRR", "MRR"."EffRate" FROM public."MRR" LOOP
			if duedate > temmrr."DateInput" then
				rateuse := temmrr."EffRate";
				temdate := duedate;
			else
				if paydate > temmrr."DateInput" 
				then
					countdelayday := temmrr."DateInput"-temdate;
					amtdelay:=amtdelay+(rateuse/100*payment/365*countdelayday);
					rateuse := temmrr."EffRate";
					temdate := temmrr."DateInput";
				else
					countdelayday := paydate - temdate;
					amtdelay := amtdelay+(rateuse/100*payment/365*countdelayday);
					outoftablemrr := false;
					EXIT;
				end if;
		
			end if;
		END LOOP;
		
		if outoftablemrr = true
		then 	
			countdelayday := paydate-temdate;
			amtdelay := amtdelay+(rateuse/100*payment/365*countdelayday);
		end if;	
	end if;
	RETURN amtdelay;
END;
$$;


ALTER FUNCTION public."CalAmtDelay"(paydate date, duedate date, payment double precision) OWNER TO dev;

--
-- TOC entry 499 (class 1255 OID 64412)
-- Dependencies: 1714 15
-- Name: CreateAccPayment(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CreateAccPayment"(text) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$DECLARE
	payment double precision;
	vatpayment double precision;
	totalpay smallint;
	accbegin  double precision;
	firstdate date;
	
	duedate date;
	Remain double precision;
	priciple double precision;
	interest double precision;
	accuint  double precision;
	waitincome  double precision;
	crdebt double precision;
	commis double precision;
	commpart double precision;
	cpacuu double precision;
	comminterest double precision;
	commaccuint  double precision;
	commcrdebt  double precision;
	commwaitincom double precision;
	commpriciple double precision;

	EffRate float;
	CommEffRate float;

BEGIN
	--Clear All old paydetails data for input IDNO
	delete from "public"."AccPayment" where ("IDNO" = $1);

	--assign value to variable from fp table
	select into payment, vatpayment, firstDate, totalpay, accbegin, commis "Fp"."P_MONTH", "Fp"."P_VAT", "Fp"."P_FDATE", "Fp"."P_TOTAL", "Fp"."P_BEGINX","Fp"."Comm" from 	public."Fp" Where ("Fp"."IDNO" = $1);
	DueDate := firstDate;	
	Remain := payment*totalpay;
	priciple := accbegin;
	interest := 0.00;
	accuint := 0.00;
	waitincome := Remain-priciple;
	crdebt := 0.00;
	commpart := 0.00;
	cpacuu := 0.00;
	comminterest := 0.00;
	commaccuint := 0.00;
	commcrdebt := 0.00;
	commpriciple := accbegin+commis;	
	commwaitincom := Remain-commpriciple;	
	--create accpayment table

	if  Remain > priciple 
	then 
		EffRate := rate(totalpay,payment,accbegin);
		CommEffRate := rate(totalpay,payment,commpriciple);
	else
		EffRate := 0 ;
		CommEffRate := 0;
	end if;
	
	FOR i IN 1..totalpay LOOP
		Remain:=Remain-payment;
		
		interest := EffRate*priciple;
		comminterest := CommEffRate*commpriciple;
		
		accuint := accuint+interest;
		commaccuint := commaccuint + comminterest;
		
		crdebt := payment-interest;
		commcrdebt := payment-comminterest;
		
		waitincome := waitincome-interest;
		commwaitincom := commwaitincom-comminterest;
		
		priciple := priciple-crdebt;
		commpriciple := commpriciple-commcrdebt;

		if commis <> 0.00
		then
			commpart := interest - comminterest;
			cpacuu := cpacuu+commpart;
			commis := commis-commpart;
		else
			commis :=0.00;
			commpart := 0.00;
			cpacuu := 0.00;
		end if;
		INSERT INTO "public"."AccPayment"("IDNO", "DueNo", "DueDate", "Remine", "Priciple","Interest","AccuInt","WaitIncome","CrDebt","Commis","CommPart","CPAcuu","CommInterest","CommAccuInt","CommCrDebt","CommWaitIncom","CommPriciple") 
				values($1,i,DueDate,Remain,priciple,interest,accuint, waitincome,crdebt,commis,commpart ,cpacuu,
				comminterest,commaccuint,commcrdebt,commwaitincom,commpriciple);
					
		DueDate:=firstdate+ (i||'month')::interval;
	END LOOP;
	RETURN true;

END;$_$;


ALTER FUNCTION public."CreateAccPayment"(text) OWNER TO dev;

--
-- TOC entry 500 (class 1255 OID 64413)
-- Dependencies: 15 1714
-- Name: CreateAccSOYPayment(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CreateAccSOYPayment"(text) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$DECLARE
	payment double precision;
	vatpayment double precision;
	totalpay smallint;
	accbegin  double precision;
	firstdate date;
	
	duedate date;
	Remain double precision;
	priciple double precision;
	interest double precision;
	accuint  double precision;
	waitincome  double precision;
	fixwic double precision;
	fixcomwic double precision;
	zima double precision;
	
	crdebt double precision;
	commis double precision;
	commpart double precision;
	cpacuu double precision;
	comminterest double precision;
	commaccuint  double precision;
	commcrdebt  double precision;
	commwaitincom double precision;
	commpriciple double precision;


BEGIN
	--Clear All old paydetails data for input IDNO
	delete from "public"."AccPayment" where ("IDNO" = $1);

	--assign value to variable from fp table
	select into payment, vatpayment, firstDate, totalpay, accbegin, commis "Fp"."P_MONTH", "Fp"."P_VAT", "Fp"."P_FDATE", "Fp"."P_TOTAL", "Fp"."P_BEGINX","Fp"."Comm" from 	public."Fp" Where ("Fp"."IDNO" = $1);
	DueDate := firstDate;	
	Remain := payment*totalpay;
	priciple := accbegin;
	interest := 0.00;
	accuint := 0.00;
	waitincome := Remain-priciple;
	fixwic := waitincome;
	zima :=0;
	crdebt := 0.00;
	commpart := 0.00;
	cpacuu := 0.00;
	comminterest := 0.00;
	commaccuint := 0.00;
	commcrdebt := 0.00;
	commpriciple := accbegin+commis;	
	commwaitincom := Remain-commpriciple;
	fixcomwic:=commwaitincom;	
	--create accpayment table

	if  Remain - priciple >-0.1
	then 	zima := totalpay*(totalpay+1)/2;
	else 	zima :=0;
	end if;
	
	FOR i IN 1..totalpay LOOP
		Remain:=Remain-payment;
		
		interest := fixwic*(totalpay+1-i)/zima;
		comminterest := fixcomwic*(totalpay+1-i)/zima;
		
		accuint := accuint+interest;
		commaccuint := commaccuint + comminterest;
		
		crdebt := payment-interest;
		commcrdebt := payment-comminterest;

		
		priciple := priciple-crdebt;
		commpriciple := commpriciple-commcrdebt;

		waitincome := Remain-priciple;
		commwaitincom := commwaitincom-comminterest;

		if commis <> 0.00
		then
			commpart := interest - comminterest;
			cpacuu := cpacuu+commpart;
			commis := commis-commpart;
		else
			commis :=0.00;
			commpart := 0.00;
			cpacuu := 0.00;
		end if;

--		INSERT INTO "public"."AccPayment"("IDNO", "DueNo", "DueDate", "Remine", "Priciple","Interest","AccuInt","WaitIncome","CrDebt","Commis","CommPart","CPAcuu","CommInterest","CommAccuInt","CommCrDebt","CommWaitIncom","CommPriciple") 
--				values($1,i,DueDate,Remain,priciple,interest,accuint, waitincome,crdebt,commis,commpart ,cpacuu,
--				comminterest,commaccuint,commcrdebt,commwaitincom,commpriciple);
					
		INSERT INTO "public"."AccPayment"("IDNO", "DueNo", "DueDate", "Remine", "Priciple","Interest","AccuInt","WaitIncome","CrDebt","Commis","CommPart","CPAcuu","CommInterest","CommAccuInt","CommCrDebt","CommWaitIncom","CommPriciple") 
				values($1,i,DueDate,round(Remain*100)/100,round(priciple*100)/100,round(interest*100)/100,round(accuint*100)/100, 
				       round(waitincome*100)/100,round(crdebt*100)/100,round(commis*100)/100,round(commpart*100)/100 ,round(cpacuu*100)/100,
				       round(comminterest*100)/100,round(commaccuint*100)/100,round(commcrdebt*100)/100,round(commwaitincom*100)/100,
				       round(commpriciple*100)/100);
				       
		DueDate:=firstdate+ (i||'month')::interval;
	END LOOP;
	RETURN true;

END;$_$;


ALTER FUNCTION public."CreateAccSOYPayment"(text) OWNER TO dev;

--
-- TOC entry 501 (class 1255 OID 64414)
-- Dependencies: 1714 15
-- Name: CreateCusPayment(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CreateCusPayment"(text) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$DECLARE
	payment double precision;
	vatpayment double precision;
	totalpay smallint;
	cusbegin  double precision;
	firstdate date;
	
	duedate date;
	Remain double precision;
	priciple double precision;
	interest double precision;
	accuint  double precision;
	waitincome  double precision;
	crdebt double precision;
	commis double precision;
	commpart double precision;
	cpacuu double precision;
	comminterest double precision;
	commaccuint  double precision;
	commcrdebt  double precision;
	commwaitincom double precision;
	commpriciple double precision;

	EffRate float;
	CommEffRate float;

BEGIN
	--Clear All old paydetails data for input IDNO
	delete from "public"."CusPayment" where ("IDNO" = $1);

	--assign value to variable from fp table
	select into payment, vatpayment, firstDate, totalpay, cusbegin, commis "Fp"."P_MONTH", "Fp"."P_VAT", "Fp"."P_FDATE", "Fp"."P_TOTAL", "Fp"."P_BEGIN","Fp"."Comm" from 	public."Fp" Where ("Fp"."IDNO" = $1);
	DueDate := firstDate;	
	Remain := payment*totalpay;
	priciple := cusbegin;
	interest := 0.00;
	accuint := 0.00;
	waitincome := Remain-priciple;
	crdebt := 0.00;
	commpart := 0.00;
	cpacuu := 0.00;
	comminterest := 0.00;
	commaccuint := 0.00;
	commcrdebt := 0.00;
	commpriciple := cusbegin+commis;	
	commwaitincom := Remain-commpriciple;	
	--create accpayment table

	if  Remain > priciple 
	then 
		EffRate := rate(totalpay,payment,cusbegin);
		CommEffRate := rate(totalpay,payment,commpriciple);
	else
		EffRate := 0 ;
		CommEffRate := 0;
	end if;
	
	FOR i IN 1..totalpay LOOP
		Remain:=Remain-payment;
		
		interest := EffRate*priciple;
		comminterest := CommEffRate*commpriciple;
		
		accuint := accuint+interest;
		commaccuint := commaccuint + comminterest;
		
		crdebt := payment-interest;
		commcrdebt := payment-comminterest;
		
		waitincome := waitincome-interest;
		commwaitincom := commwaitincom-comminterest;
		
		priciple := priciple-crdebt;
		commpriciple := commpriciple-commcrdebt;

		if commis <> 0.00
		then
			commpart := interest - comminterest;
			cpacuu := cpacuu+commpart;
			commis := commis-commpart;
		else
			commis :=0.00;
			commpart := 0.00;
			cpacuu := 0.00;
		end if;
		INSERT INTO "public"."CusPayment"("IDNO", "DueNo", "DueDate","Remine", "Priciple","Interest","AccuInt","WaitIncome","CrDebt","Commis","CommPart","CPAcuu","CommInterest","CommAccuInt","CommCrDebt","CommWaitIncom","CommPriciple") 
				values($1,i,DueDate,Remain,priciple,interest,accuint, waitincome,crdebt,commis,commpart ,cpacuu,
				comminterest,commaccuint,commcrdebt,commwaitincom,commpriciple);
					
		DueDate:=firstdate+ (i||'month')::interval;
	END LOOP;
	RETURN true;

END;$_$;


ALTER FUNCTION public."CreateCusPayment"(text) OWNER TO dev;

--
-- TOC entry 502 (class 1255 OID 64415)
-- Dependencies: 15 1714
-- Name: CreateCusSOYPayment(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CreateCusSOYPayment"(text) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$DECLARE
	payment double precision;
	vatpayment double precision;
	totalpay smallint;
	accbegin  double precision;
	firstdate date;
	
	duedate date;
	Remain double precision;
	priciple double precision;
	interest double precision;
	accuint  double precision;
	waitincome  double precision;
	fixwic double precision;
	zima double precision;
	
	crdebt double precision;
	commis double precision;
	commpart double precision;
	cpacuu double precision;
	comminterest double precision;
	commaccuint  double precision;
	commcrdebt  double precision;
	commwaitincom double precision;
	commpriciple double precision;


BEGIN
	--Clear All old paydetails data for input IDNO
	delete from "public"."CusPayment" where ("IDNO" = $1);

	--assign value to variable from fp table
	select into payment, vatpayment, firstDate, totalpay, accbegin, commis "Fp"."P_MONTH", "Fp"."P_VAT", "Fp"."P_FDATE", "Fp"."P_TOTAL", "Fp"."P_BEGINX","Fp"."Comm" from 	public."Fp" Where ("Fp"."IDNO" = $1);
	DueDate := firstDate;	
	Remain := payment*totalpay;
	priciple := accbegin;
	interest := 0.00;
	accuint := 0.00;
	waitincome := Remain-priciple;
	fixwic := waitincome;
	zima :=0;
	crdebt := 0.00;
	commpart := 0.00;
	cpacuu := 0.00;
	comminterest := 0.00;
	commaccuint := 0.00;
	commcrdebt := 0.00;
	commpriciple := accbegin+commis;	
	commwaitincom := Remain-commpriciple;	
	--create accpayment table

	if  Remain - priciple > -0.1
	then 	zima := totalpay*(totalpay+1)/2;
	else 	zima :=0;
	end if;
	
	FOR i IN 1..totalpay LOOP
		Remain:=Remain-payment;
		
		interest := fixwic*(totalpay+1-i)/zima;
		comminterest := 0;
		
		accuint := accuint+interest;
		commaccuint := 0;
		
		crdebt := payment-interest;
		commcrdebt := 0;
		
		priciple := priciple-crdebt;
		commpriciple := 0;

		waitincome := Remain-priciple;
		commwaitincom := 0;

--		if commis <> 0.00
--		then
--			commpart := interest - comminterest;
--			cpacuu := cpacuu+commpart;
--			commis := commis-commpart;
--		else
--			commis :=0.00;
--			commpart := 0.00;
--			cpacuu := 0.00;
--		end if;

		INSERT INTO "public"."CusPayment"("IDNO", "DueNo", "DueDate", "Remine", "Priciple","Interest","AccuInt","WaitIncome","CrDebt","Commis","CommPart","CPAcuu","CommInterest","CommAccuInt","CommCrDebt","CommWaitIncom","CommPriciple") 
				values($1,i,DueDate,Remain,priciple,interest,accuint, waitincome,crdebt,commis,commpart ,cpacuu,
				comminterest,commaccuint,commcrdebt,commwaitincom,commpriciple);
					
		DueDate:=firstdate+ (i||'month')::interval;
	END LOOP;
	RETURN true;

END;$_$;


ALTER FUNCTION public."CreateCusSOYPayment"(text) OWNER TO dev;

--
-- TOC entry 503 (class 1255 OID 64416)
-- Dependencies: 1714 15
-- Name: CrtAccEFTPaymentAllYear(integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CrtAccEFTPaymentAllYear"(custyear integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfp "Fp"%ROWTYPE;
	sucess boolean;

BEGIN
	if custyear >=2008 then
		for temfp in select * from public."Fp" Where ("Fp"."P_CustByYear" = custyear) loop

			if  (temfp."P_BEGINX" <> 0 or temfp."P_BEGINX" <> null) and temfp."P_TOTAL" <> 0 then
				sucess := "CreateAccPayment"(temfp."IDNO");
			end if;

		end loop;

		RETURN true;
	else
		return false;
	end if;

END;$$;


ALTER FUNCTION public."CrtAccEFTPaymentAllYear"(custyear integer) OWNER TO dev;

--
-- TOC entry 504 (class 1255 OID 64417)
-- Dependencies: 1714 15
-- Name: CrtAccSOYPaymentAllYear(integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CrtAccSOYPaymentAllYear"(custyear integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfp "Fp"%ROWTYPE;
	sucess boolean;

BEGIN
	if custyear <=2007 then
		for temfp in select * from public."Fp" Where ("Fp"."P_CustByYear" = custyear) loop

			delete from "public"."AccPayment" where ("IDNO" = temfp."IDNO");

			sucess := "CreateAccSOYPayment"(temfp."IDNO");

		end loop;

		RETURN true;
	else
		return false;
	end if;

END;$$;


ALTER FUNCTION public."CrtAccSOYPaymentAllYear"(custyear integer) OWNER TO dev;

--
-- TOC entry 505 (class 1255 OID 64418)
-- Dependencies: 15 1714
-- Name: CrtCusEFTPaymentAllYear(integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CrtCusEFTPaymentAllYear"(custyear integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfp "Fp"%ROWTYPE;
	sucess boolean;

BEGIN
	if custyear >=2008 then
		for temfp in select * from public."Fp" Where ("Fp"."P_CustByYear" = custyear) loop

			delete from "public"."CusPayment" where ("IDNO" = temfp."IDNO");

			sucess := "CreateCusPayment"(temfp."IDNO");

		end loop;

		RETURN true;
	else
		return false;
	end if;

END;$$;


ALTER FUNCTION public."CrtCusEFTPaymentAllYear"(custyear integer) OWNER TO dev;

--
-- TOC entry 506 (class 1255 OID 64419)
-- Dependencies: 15 1714
-- Name: CrtCusSOYPaymentAllYear(integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CrtCusSOYPaymentAllYear"(custyear integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfp "Fp"%ROWTYPE;
	sucess boolean;

BEGIN
	if custyear <=2007 then
		for temfp in select * from public."Fp" Where ("Fp"."P_CustByYear" = custyear) loop

			delete from "public"."CusPayment" where ("IDNO" = temfp."IDNO");

			sucess := "CreateCusSOYPayment"(temfp."IDNO");

		end loop;

		RETURN true;
	else
		return false;
	end if;

END;$$;


ALTER FUNCTION public."CrtCusSOYPaymentAllYear"(custyear integer) OWNER TO dev;

--
-- TOC entry 507 (class 1255 OID 64420)
-- Dependencies: 1714 15
-- Name: CrtTranAccPayment(text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CrtTranAccPayment"(oldid text, trfid text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	oldpayment "AccPayment"%ROWTYPE;
	oldtotal integer;
	newtotal integer;
	lastoldrec integer;
	i integer;
	
	DueDate date;
	firstdate date;
	temaccint double precision;
	temCPAcuu double precision;
	temCommAccint double precision;
	tembeginx double precision;
	
BEGIN

	select into oldtotal  "Fp"."P_TOTAL" from public."Fp" Where ("Fp"."IDNO" = oldid);
	select into newtotal,firstdate  "Fp"."P_TOTAL","Fp"."P_FDATE" from public."Fp" Where ("Fp"."IDNO" = trfid);
	lastoldrec := oldtotal-newtotal;

	--Clear All old paydetails data for input IDNO
	delete from "public"."AccPayment" where ("IDNO" = trfid);

	--assign value to variable from fp table
	i:=1;
	DueDate := firstdate;
	temaccint := 0;
	temCPAcuu := 0;
	temCommAccint := 0;
	
	FOR oldpayment in select * from "AccPayment" where "IDNO" = oldid LOOP
		if oldpayment."DueNo"=lastoldrec then
			tembeginx := oldpayment."Priciple";
		end if;
		
		if oldpayment."DueNo" >  lastoldrec then		
			temaccint:= temaccint + oldpayment."Interest";
			temCPAcuu:= temCPAcuu + oldpayment."CommPart";
			temCommAccint:= temCommAccint + oldpayment."CommInterest";
			
			INSERT INTO "public"."AccPayment"("IDNO", "DueNo", "DueDate", "Remine", "Priciple","Interest","AccuInt","WaitIncome","CrDebt","Commis","CommPart","CPAcuu","CommInterest","CommAccuInt","CommCrDebt","CommWaitIncom","CommPriciple") 
				values(trfid,i,DueDate,
					oldpayment."Remine",
					oldpayment."Priciple",
					oldpayment."Interest",
					temaccint,
					oldpayment."WaitIncome",
					oldpayment."CrDebt",
					oldpayment."Commis",
					oldpayment."CommPart",
					temCPAcuu,
					oldpayment."CommInterest",
					temCommAccint,
					oldpayment."CommCrDebt",
					oldpayment."CommWaitIncom",
					oldpayment."CommPriciple");
				       
			DueDate:=firstdate+ (i||'month')::interval;
			i:=i+1;		
		end if;
	END LOOP;
	
	update "Fp" set "P_BEGINX" = tembeginx where "IDNO" = trfid;
	
	RETURN true;

END;$$;


ALTER FUNCTION public."CrtTranAccPayment"(oldid text, trfid text) OWNER TO dev;

--
-- TOC entry 508 (class 1255 OID 64421)
-- Dependencies: 15 1714
-- Name: CrtTranAccPaymentAllYear(integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "CrtTranAccPaymentAllYear"(yy integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfp "Fp"%ROWTYPE;
	searchtrid text;
	ready boolean;
BEGIN
	FOR temfp in select * from "Fp" where  "P_CustByYear" = yy and "P_TransferIDNO" is not null  LOOP
		ready := "CrtTranAccPayment"(temfp."IDNO",temfp."P_TransferIDNO");
	END LOOP;
	RETURN true;

END;$$;


ALTER FUNCTION public."CrtTranAccPaymentAllYear"(yy integer) OWNER TO dev;

--
-- TOC entry 509 (class 1255 OID 64422)
-- Dependencies: 1714 15
-- Name: LogsAny(text, text, date, text, date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "LogsAny"(menu text, uid text, timeopen date, refid text, timeclose date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
BEGIN
	INSERT INTO "public"."LogsAnyFunction"("id_menu", "user_id", "time_open", "ref_id", "time_close") values (menu,uid,timeopen,refid,timeclose);
RETURN true;
END;$$;


ALTER FUNCTION public."LogsAny"(menu text, uid text, timeopen date, refid text, timeclose date) OWNER TO dev;

--
-- TOC entry 510 (class 1255 OID 64423)
-- Dependencies: 1714 15
-- Name: UpdateRef1Ref2(); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION "UpdateRef1Ref2"() RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	contact "VContact"%ROWTYPE;
	temref1 text;
	temref2 text;

BEGIN
	FOR  contact in select * from "VContact" where  ("TranIDRef1" is null or "TranIDRef2" is null) and "P_TOTAL" <>0  LOOP
		temref1:=gen_encode_ref1(contact."IDNO");
		temref2:=Trim(substring(contact."carnum" from 9 for 9));
		update "Fp" set "TranIDRef1"=temref1, "TranIDRef2"=temref2 where "IDNO"=contact."IDNO";
	END LOOP;
	RETURN true;

END;$$;


ALTER FUNCTION public."UpdateRef1Ref2"() OWNER TO dev;

--
-- TOC entry 511 (class 1255 OID 64424)
-- Dependencies: 15 1714
-- Name: accept_acc_cash(text, date, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION accept_acc_cash(postid text, recdate date, userid text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	temfcash "FCash"%ROWTYPE;
	temidno varchar(25);
	temrecno varchar(10);
	temvatno varchar(10);
	pmonth double precision;
	pvat double precision;
	ptotal integer;
	temamtpay double precision;
	dueno integer;
	duedate date;
	comefrom text;
	firstdate date;
	timetopay integer;
	usevat boolean;
	paidstate boolean;
	paybefore boolean:=false;
	anyreference text;
	custyear integer;
	typerec char(1);
BEGIN
	select into comefrom "PostLog".paytype from "PostLog" where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;
	if found then		
		update "PostLog" set "AcceptPost"=true, "UserIDAccept"=userid  
		where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;
		
		for temfcash in select * from "FCash" where "FCash".refreceipt is null and "FCash"."PostID" = postid loop
		    if temfcash."IDNO" is null then
		        update "FCash" set "IDNO" = 'ลูกค้านอก'where "FCash"."PostID"=postid;
		    else
			temidno := temfcash."IDNO";
			temamtpay := temfcash."AmtPay";
			if temfcash."TypePay"=1 then
				temrecno := gen_rec_no(recdate);	
				update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";
						
				select into pmonth,pvat,firstdate,custyear,ptotal  "Fp"."P_MONTH","Fp"."P_VAT","Fp"."P_FDATE","Fp"."P_CustByYear","Fp"."P_TOTAL"
				from "Fp" where "Fp"."IDNO"= temidno;
				timetopay := temamtpay/(pmonth+pvat);
				
				select count(*) into dueno from "Fr" where "Fr"."IDNO" = temidno and "Fr"."Cancel"=false and "Fr"."R_DueNo"<99 and "Fr"."R_DueNo">0;
				for j in 1..timetopay loop
					dueno:=dueno+1;
					insert into "Fr" values (temidno,dueno,temrecno,recdate,pmonth,'CA',current_date,comefrom,false,null,null,custyear);

					if dueno = ptotal then
						update "Fp" set "P_ACCLOSE"=true , "P_CLDATE"=recdate  where "Fp"."IDNO"=temidno;
					end if;

					----------------check vat print out already
					duedate := firstdate;
					select into  paidstate "FVat"."Paid_Status"  from "FVat" where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
					if found then
						update "FVat" set "Paid_Status" = true  where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
					else
						for i in 1..dueno-1 loop
							duedate := firstdate+ (i||'month')::interval;
						end loop;
		
						if duedate <= recdate  then
							temvatno := gen_vat_no(duedate);
						else
							if paybefore = false then 
								temvatno := gen_vat_no(recdate);
								paybefore:= true;
							end if;
							duedate := recdate;
						end if;
	
						insert into "FVat" values (temidno,dueno,temvatno,duedate,pvat,current_date,true,false,null);
					end if;
					-------------------- end check vat ------------------
				end loop;
			else
---				select into usevat  "TypePay"."UseVat" from "TypePay" where "TypePay"."TypeID"=temfcash."TypePay";
				select into usevat,typerec  "TypePay"."UseVat", "TypePay"."TypeRec" from "TypePay" where "TypePay"."TypeID"=temfcash."TypePay";

				if usevat=true then
					select into custyear "Fp"."P_CustByYear" from "Fp" where "Fp"."IDNO"= temidno;
					temrecno := gen_rec_no(recdate);
					update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";
					pvat := temamtpay - amt_before_vat(temamtpay);	
					anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);
					insert into "Fr" values (temidno,temfcash."TypePay",temrecno,recdate,amt_before_vat(temamtpay),'CA',current_date,comefrom,false,null,anyreference,null);

					temvatno := gen_vat_no(recdate);
					insert into "FVat" values (temidno,temfcash."TypePay",temvatno,recdate,round(cast(pvat as numeric),2),current_date,true,false,temrecno);

				else

					if typerec = 'K' then 
						temrecno := gen_k_no(recdate);
						insert into "FOtherpay" values (temidno,recdate,temrecno,temamtpay,temfcash."TypePay",'CA',current_date,comefrom,false,null,anyreference);

					elsif typerec='N' then 
						temrecno := gen_n_no(recdate);
						anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);
						insert into "FOtherpay" values (temidno,recdate,temrecno,temamtpay,temfcash."TypePay",'CA',current_date,comefrom,false,null,anyreference);

					elsif typerec='R' then 
						temrecno := gen_rec_no(recdate);
						anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);
						insert into "Fr" values (temidno,temfcash."TypePay",temrecno,recdate,temamtpay,'CA',current_date,comefrom,false,null,anyreference,custyear);

					end if;

					update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";


				
--					if temfcash."TypePay" >= 200 and temfcash."TypePay" < 300 then
--						temrecno := gen_k_no(recdate);
--					else
--						temrecno := gen_n_no(recdate);
--					end if;
--					update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";
--					anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);
--					insert into "FOtherpay" values (temidno,recdate,temrecno,temamtpay,temfcash."TypePay",'CA',current_date,comefrom,false,null,anyreference);
				end if;
			end if;
		    end if;
		end loop;
	end if;
	RETURN true;
END;
$$;


ALTER FUNCTION public.accept_acc_cash(postid text, recdate date, userid text) OWNER TO dev;

--
-- TOC entry 512 (class 1255 OID 64425)
-- Dependencies: 15 1714
-- Name: accept_cancel_stopvat(text, date, double precision, double precision); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION accept_cancel_stopvat(idno text, recdate date, useamt double precision, usesl double precision) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	outrecno text:='';
	
	temdpremain "VDepositRemain"%ROWTYPE;
	remainamt double precision;
	recno text;
	temvatno text;
	k_recno text;
	temremain double precision;
	usedep double precision;
	usesameno boolean:=false;
	paidstate boolean;

	pmonth double precision;
	pvat double precision;
	firstdate date;
	duedate date;
	ptotal integer;
	timetopay integer;
	dueno integer;
	custyear integer;
BEGIN
	recno:=gen_rec_no(recdate);
	
	remainamt:=0;
	temremain:=useamt;
	for temdpremain in select * from "VDepositRemain" where "IDNO" = idno loop
		usedep := 0;
		if temdpremain.remain is null then 
			remainamt:=remainamt+temdpremain."O_MONEY";
			usedep:=temdpremain."O_MONEY";
		else
			remainamt:= remainamt+temdpremain.remain;
			usedep:=temdpremain.remain;
		end if;
		
		if useamt > remainamt then
			k_recno:=gen_k_no(recdate);
			insert into "FOtherpay" values(idno,recdate,k_recno,-usedep,299,'CA',current_date,'DEP',false,temdpremain."O_RECEIPT",recno);
			
		else ---  useamt <= remainamt 
		
			k_recno:=gen_k_no(recdate);
			insert into "FOtherpay" values(idno,recdate,k_recno,-temremain,299,'CA',current_date,'DEP',false,temdpremain."O_RECEIPT",recno);
		
		end if;
		temremain:=temremain-usedep;
	end loop;

	-----------  use deposit to  any payment ---------------


		select into pmonth,pvat,firstdate,custyear,ptotal  "Fp"."P_MONTH","Fp"."P_VAT","Fp"."P_FDATE","Fp"."P_CustByYear","Fp"."P_TOTAL" 
		from "Fp" where "Fp"."IDNO"= idno;
		timetopay := useamt/(pmonth+pvat);
				
		select count(*) into dueno from "Fr" where "Fr"."IDNO" = idno and "Fr"."Cancel"=false and "Fr"."R_DueNo"<99 and "Fr"."R_DueNo">0;
		for j in 1..timetopay loop
			dueno:=dueno+1;
			insert into "Fr" values (idno,dueno,recno,recdate,pmonth,'CA',current_date,'DEP',false,null,null,custyear);

			if dueno = ptotal then
				update "Fp" set "P_ACCLOSE"=true , "P_CLDATE"=recdate  where "Fp"."IDNO"=idno;
			end if;

			----------------check vat print out already

			select into  paidstate "FVat"."Paid_Status"  from "FVat" where "FVat"."IDNO"=idno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
			if found then
				update "FVat" set "Paid_Status" = true  where "FVat"."IDNO"=idno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
			else

				if usesameno = false then 
					temvatno := gen_vat_no(recdate);
					usesameno:= true;
				end if;
				duedate := recdate;

				insert into "FVat" values (idno,dueno,temvatno,duedate,pvat,current_date,true,false,null);
			end if;
			-------------------- end check vat ------------------
		end loop;----  for j
		outrecno :=  recno;

		return outrecno;	
END;
$$;


ALTER FUNCTION public.accept_cancel_stopvat(idno text, recdate date, useamt double precision, usesl double precision) OWNER TO dev;

--
-- TOC entry 513 (class 1255 OID 64426)
-- Dependencies: 15 1714
-- Name: accept_cash_postlog(text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION accept_cash_postlog(postid text, userid text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

	temfcash "FCash"%ROWTYPE;

	temidno varchar(25);

	temrecno varchar(10);

	temvatno varchar(10);

	pmonth double precision;

	pvat double precision;

	ptotal integer;

	temamtpay double precision;

	dueno integer;

	duedate date;

	postdate date;

	firstdate date;

	timetopay integer;

	usevat boolean;

	paidstate boolean;

	paybefore boolean:=false;

	anyreference text;

	custyear integer;

	typerec char(1);

BEGIN

	select into postdate "PostLog"."PostDate" from "PostLog" where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;

	if found then

		update "PostLog" set "AcceptPost"=true, "UserIDAccept"=userid,"PostTime"=current_time

		where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;

		for temfcash in select * from "FCash" where "FCash".refreceipt is null and "FCash"."PostID" = postid loop

		    if temfcash."IDNO" is null then

		        update "FCash" set "IDNO" = 'ลูกค้านอก'where "FCash"."PostID"=postid;

		    else

			temidno := temfcash."IDNO";

			temamtpay := temfcash."AmtPay";

			if temfcash."TypePay"=1 then

				temrecno := gen_rec_no(postdate);	

				update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";

				select into pmonth,pvat,firstdate,custyear,ptotal  "Fp"."P_MONTH","Fp"."P_VAT","Fp"."P_FDATE","Fp"."P_CustByYear","Fp"."P_TOTAL"

				from "Fp" where "Fp"."IDNO"= temidno;

				timetopay := temamtpay/(pmonth+pvat);

				select count(*) into dueno from "Fr" where "Fr"."IDNO" = temidno and "Fr"."Cancel"=false and "Fr"."R_DueNo"<99 and "Fr"."R_DueNo">0;

				for j in 1..timetopay loop

					dueno:=dueno+1;

					insert into "Fr" values (temidno,dueno,temrecno,current_date,pmonth,'CA',current_date,'OC',false,null,null,custyear);

					if dueno = ptotal then

						update "Fp" set "P_ACCLOSE"=true , "P_CLDATE"=current_date  where "Fp"."IDNO"=temidno;

					end if;

					----------------check vat print out already

					duedate := firstdate;

					select into  paidstate "FVat"."Paid_Status"  from "FVat" where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;

					if found then

						update "FVat" set "Paid_Status" = true  where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;

					else

						for i in 1..dueno-1 loop

							duedate := firstdate+ (i||'month')::interval;

						end loop;

						if duedate <= current_date  then

							temvatno := gen_vat_no(duedate);

						else

							if paybefore = false then 

								temvatno := gen_vat_no(current_date);

								paybefore:= true;

							end if;

							duedate := current_date;

						end if;

						insert into "FVat" values (temidno,dueno,temvatno,duedate,pvat,current_date,true,false,null);

					end if;
					-------------------- end check vat ------------------

				end loop;

			else

--				select into usevat  "TypePay"."UseVat" from "TypePay" where "TypePay"."TypeID"=temfcash."TypePay";

				select into usevat,typerec  "TypePay"."UseVat", "TypePay"."TypeRec" from "TypePay" where "TypePay"."TypeID"=temfcash."TypePay";

				if usevat=true then

					select into custyear "Fp"."P_CustByYear" from "Fp" where "Fp"."IDNO"= temidno;

					temrecno := gen_rec_no(postdate);

					update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";

					pvat := temamtpay - amt_before_vat(temamtpay);	

					anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);

					insert into "Fr" values (temidno,temfcash."TypePay",temrecno,postdate,amt_before_vat(temamtpay),'CA',current_date,'OC',false,null,anyreference,null);

					temvatno := gen_vat_no(postdate);

					insert into "FVat" values (temidno,temfcash."TypePay",temvatno,postdate,round(cast(pvat as numeric),2),current_date,true,false,temrecno);

				else

					if typerec = 'K' then 

						temrecno := gen_k_no(postdate);

						insert into "FOtherpay" values (temidno,postdate,temrecno,temamtpay,temfcash."TypePay",'CA',current_date,'OC',false,null,null);

					elsif typerec='N' then 

						temrecno := gen_n_no(postdate);

						anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);

						insert into "FOtherpay" values (temidno,postdate,temrecno,temamtpay,temfcash."TypePay",'CA',current_date,'OC',false,null,anyreference);

					elsif typerec='R' then 

						temrecno := gen_rec_no(postdate);

						anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);

						insert into "Fr" values (temidno,temfcash."TypePay",temrecno,postdate,temamtpay,'CA',current_date,'OC',false,null,anyreference,custyear);

					end if;

					update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";


--					if temfcash."TypePay" >= 200 and temfcash."TypePay" < 300 then

--						temrecno := gen_k_no(postdate);

--					else

--						temrecno := gen_n_no(postdate);

--					end if;

--					update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";

--					anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);					

--					insert into "FOtherpay" values (temidno,postdate,temrecno,temamtpay,temfcash."TypePay",'CA',current_date,'OC',false,null,anyreference);

				end if;

			end if;

		    end if;

		end loop;

	end if;

	RETURN true;

END;

$$;


ALTER FUNCTION public.accept_cash_postlog(postid text, userid text) OWNER TO dev;

--
-- TOC entry 514 (class 1255 OID 64427)
-- Dependencies: 15 1714
-- Name: accept_cheque_postlog(text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION accept_cheque_postlog(postid text, userid text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	countrec integer;

BEGIN
	select count(*) into countrec from "PostLog" where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;
	if found then
		update "PostLog" set "AcceptPost"=true, "UserIDAccept"=userid  
		where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;

		update "FCheque" set "Accept"=true where "FCheque"."PostID"=postid ;	
	end if;
	RETURN true;
END;
$$;


ALTER FUNCTION public.accept_cheque_postlog(postid text, userid text) OWNER TO dev;

--
-- TOC entry 515 (class 1255 OID 64428)
-- Dependencies: 15 1714
-- Name: accept_tac_postlog(text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION accept_tac_postlog(postid text, userid text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

    temfcash "FTACCheque"%ROWTYPE;

    temidno varchar(25);

    temrecno varchar(10);

    temvatno varchar(10);

    pmonth double precision;

    pvat double precision;

    ptotal integer;

    temamtpay double precision;

    dueno integer;

    duedate date;

    postdate date;

    firstdate date;

    timetopay integer;

    usevat boolean;

    paidstate boolean;

    paybefore boolean:=false;

    anyreference text;

    custyear integer;

    typerec char(1);

BEGIN

    select into postdate "PostLog"."PostDate" from "PostLog" where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;

    if found then

        update "PostLog" set "AcceptPost"=true, "UserIDAccept"=userid,"PostTime"=current_time

        where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;

        for temfcash in select * from "FTACCheque" where "FTACCheque".refreceipt is null and "FTACCheque"."PostID" = postid loop

            if temfcash."COID" is null then

                update "FTACCheque" set "COID" = 'ลูกค้านอก'where "FTACCheque"."PostID"=postid;

            else

            temidno := temfcash."COID";

            temamtpay := temfcash."AmtPay";

            if temfcash."TypePay"=1 then

                temrecno := gen_rec_no(postdate);    

                update "FTACCheque" set refreceipt = temrecno where "FTACCheque"."PostID"=postid and "FTACCheque"."TypePay"=temfcash."TypePay";

                select into pmonth,pvat,firstdate,custyear,ptotal  "Fp"."P_MONTH","Fp"."P_VAT","Fp"."P_FDATE","Fp"."P_CustByYear","Fp"."P_TOTAL" 

                from "Fp" where "Fp"."IDNO"= temidno;

                timetopay := temamtpay/(pmonth+pvat);

                select count(*) into dueno from "Fr" where "Fr"."IDNO" = temidno and "Fr"."Cancel"=false and "Fr"."R_DueNo"<99 and "Fr"."R_DueNo">0;

                for j in 1..timetopay loop

                    dueno:=dueno+1;

                    insert into "Fr" values (temidno,dueno,temrecno,current_date,pmonth,'TC',current_date,'TCQ',false,null,null,custyear);

                    if dueno = ptotal then

                        update "Fp" set "P_ACCLOSE"=true , "P_CLDATE"=current_date  where "Fp"."IDNO"=temidno;

                    end if;

                    ----------------check vat print out already

                    duedate := firstdate;

                    select into  paidstate "FVat"."Paid_Status"  from "FVat" where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;

                    if found then

                        update "FVat" set "Paid_Status" = true  where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;

                    else

                        for i in 1..dueno-1 loop

                            duedate := firstdate+ (i||'month')::interval;

                        end loop;

                        if duedate <= current_date  then

                            temvatno := gen_vat_no(duedate);

                        else

                            if paybefore = false then 

                                temvatno := gen_vat_no(current_date);

                                paybefore:= true;

                            end if;

                            duedate := current_date;

                        end if;

                        insert into "FVat" values (temidno,dueno,temvatno,duedate,pvat,current_date,true,false,null);

                    end if;

                    -------------------- end check vat ------------------

                end loop;

            else

--                select into usevat  "TypePay"."UseVat" from "TypePay" where "TypePay"."TypeID"=temfcash."TypePay";

                select into usevat,typerec  "TypePay"."UseVat", "TypePay"."TypeRec" from "TypePay" where "TypePay"."TypeID"=temfcash."TypePay";

                if usevat=true then

                    select into custyear "Fp"."P_CustByYear" from "Fp" where "Fp"."IDNO"= temidno;

                    temrecno := gen_rec_no(postdate);

                    update "FTACCheque" set refreceipt = temrecno where "FTACCheque"."PostID"=postid and "FTACCheque"."TypePay"=temfcash."TypePay";

                    pvat := temamtpay - amt_before_vat(temamtpay);    

                    anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);

                    insert into "Fr" values (temidno,temfcash."TypePay",temrecno,postdate,amt_before_vat(temamtpay),'TC',current_date,'TCQ',false,null,anyreference,null);

                    temvatno := gen_vat_no(postdate);

                    insert into "FVat" values (temidno,temfcash."TypePay",temvatno,postdate,round(cast(pvat as numeric),2),current_date,true,false,temrecno);

                else

                    if typerec = 'K' then 

                        temrecno := gen_k_no(postdate);

                        insert into "FOtherpay" values (temidno,postdate,temrecno,temamtpay,temfcash."TypePay",'TC',current_date,'TCQ',false,null,null);

                    elsif typerec='N' then 

                        temrecno := gen_n_no(postdate);

                        anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);

                        insert into "FOtherpay" values (temidno,postdate,temrecno,temamtpay,temfcash."TypePay",'TC',current_date,'TCQ',false,null,anyreference);

                    elsif typerec='R' then 

                        temrecno := gen_rec_no(postdate);

                        anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);

                        insert into "Fr" values (temidno,temfcash."TypePay",temrecno,postdate,temamtpay,'TC',current_date,'TCQ',false,null,anyreference,custyear);

                    end if;

                    update "FTACCheque" set refreceipt = temrecno where "FTACCheque"."PostID"=postid and "FTACCheque"."TypePay"=temfcash."TypePay";

--                    if temfcash."TypePay" >= 200 and temfcash."TypePay" < 300 then

--                        temrecno := gen_k_no(postdate);

--                    else

--                        temrecno := gen_n_no(postdate);

--                    end if;

--                    update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";

--                    anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);                    

--                    insert into "FOtherpay" values (temidno,postdate,temrecno,temamtpay,temfcash."TypePay",'CA',current_date,'OC',false,null,anyreference);

                end if;

            end if;

            end if;

        end loop;

    end if;

    RETURN true;

END;

$$;


ALTER FUNCTION public.accept_tac_postlog(postid text, userid text) OWNER TO dev;

--
-- TOC entry 516 (class 1255 OID 64429)
-- Dependencies: 15 1714
-- Name: accept_tactr_postlog(text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION accept_tactr_postlog(postid text, userid text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

    temfcash "FTACTran"%ROWTYPE;

    temidno varchar(25);

    temrecno varchar(10);

    temvatno varchar(10);

    pmonth double precision;

    pvat double precision;

    ptotal integer;

    temamtpay double precision;

    dueno integer;

    duedate date;

    postdate date;

    firstdate date;

    timetopay integer;

    usevat boolean;

    paidstate boolean;

    paybefore boolean:=false;

    anyreference text;

    custyear integer;

    typerec char(1);

BEGIN

    select into postdate "PostLog"."PostDate" from "PostLog" where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;

    if found then

        update "PostLog" set "AcceptPost"=true, "UserIDAccept"=userid,"PostTime"=current_time

        where "PostLog"."AcceptPost"=false and "PostLog"."PostID"=postid;

        for temfcash in select * from "FTACTran" where "FTACTran".refreceipt is null and "FTACTran"."PostID" = postid loop

            if temfcash."COID" is null then

                update "FTACTran" set "COID" = 'ลูกค้านอก'where "FTACTran"."PostID"=postid;

            else

            temidno := temfcash."COID";

            temamtpay := temfcash."AmtPay";

            if temfcash."TypePay"=1 then

                temrecno := gen_rec_no(postdate);    

                update "FTACTran" set refreceipt = temrecno where "FTACTran"."PostID"=postid and "FTACTran"."TypePay"=temfcash."TypePay";

                select into pmonth,pvat,firstdate,custyear,ptotal  "Fp"."P_MONTH","Fp"."P_VAT","Fp"."P_FDATE","Fp"."P_CustByYear","Fp"."P_TOTAL" 

                from "Fp" where "Fp"."IDNO"= temidno;

                timetopay := temamtpay/(pmonth+pvat);

                select count(*) into dueno from "Fr" where "Fr"."IDNO" = temidno and "Fr"."Cancel"=false and "Fr"."R_DueNo"<99 and "Fr"."R_DueNo">0;

                for j in 1..timetopay loop

                    dueno:=dueno+1;

                    insert into "Fr" values (temidno,dueno,temrecno,current_date,pmonth,'TC',current_date,'TCQ',false,null,null,custyear);

                    if dueno = ptotal then

                        update "Fp" set "P_ACCLOSE"=true , "P_CLDATE"=current_date  where "Fp"."IDNO"=temidno;

                    end if;

                    ----------------check vat print out already

                    duedate := firstdate;

                    select into  paidstate "FVat"."Paid_Status"  from "FVat" where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;

                    if found then

                        update "FVat" set "Paid_Status" = true  where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;

                    else

                        for i in 1..dueno-1 loop

                            duedate := firstdate+ (i||'month')::interval;

                        end loop;

                        if duedate <= current_date  then

                            temvatno := gen_vat_no(duedate);

                        else

                            if paybefore = false then 

                                temvatno := gen_vat_no(current_date);

                                paybefore:= true;

                            end if;

                            duedate := current_date;

                        end if;

                        insert into "FVat" values (temidno,dueno,temvatno,duedate,pvat,current_date,true,false,null);

                    end if;

                    -------------------- end check vat ------------------

                end loop;

            else

--                select into usevat  "TypePay"."UseVat" from "TypePay" where "TypePay"."TypeID"=temfcash."TypePay";

                select into usevat,typerec  "TypePay"."UseVat", "TypePay"."TypeRec" from "TypePay" where "TypePay"."TypeID"=temfcash."TypePay";

                if usevat=true then

                    select into custyear "Fp"."P_CustByYear" from "Fp" where "Fp"."IDNO"= temidno;

                    temrecno := gen_rec_no(postdate);

                    update "FTACTran" set refreceipt = temrecno where "FTACTran"."PostID"=postid and "FTACTran"."TypePay"=temfcash."TypePay";

                    pvat := temamtpay - amt_before_vat(temamtpay);    

                    anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);

                    insert into "Fr" values (temidno,temfcash."TypePay",temrecno,postdate,amt_before_vat(temamtpay),'TC',current_date,'TCQ',false,null,anyreference,null);

                    temvatno := gen_vat_no(postdate);

                    insert into "FVat" values (temidno,temfcash."TypePay",temvatno,postdate,round(cast(pvat as numeric),2),current_date,true,false,temrecno);

                else

                    if typerec = 'K' then 

                        temrecno := gen_k_no(postdate);

                        insert into "FOtherpay" values (temidno,postdate,temrecno,temamtpay,temfcash."TypePay",'TT',current_date,'TTR',false,null,null);

                    elsif typerec='N' then 

                        temrecno := gen_n_no(postdate);

                        anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);

                        insert into "FOtherpay" values (temidno,postdate,temrecno,temamtpay,temfcash."TypePay",'TT',current_date,'TTR',false,null,anyreference);

                    elsif typerec='R' then 

                        temrecno := gen_rec_no(postdate);

                        anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);

                        insert into "Fr" values (temidno,temfcash."TypePay",temrecno,postdate,temamtpay,'TT',current_date,'TTR',false,null,anyreference,custyear);

                    end if;

                    update "FTACTran" set refreceipt = temrecno where "FTACTran"."PostID"=postid and "FTACTran"."TypePay"=temfcash."TypePay";

--                    if temfcash."TypePay" >= 200 and temfcash."TypePay" < 300 then

--                        temrecno := gen_k_no(postdate);

--                    else

--                        temrecno := gen_n_no(postdate);

--                    end if;

--                    update "FCash" set refreceipt = temrecno where "FCash"."PostID"=postid and "FCash"."TypePay"=temfcash."TypePay";

--                    anyreference := post_any_reference(temfcash."TypePay",temidno,temamtpay);                    

--                    insert into "FOtherpay" values (temidno,postdate,temrecno,temamtpay,temfcash."TypePay",'CA',current_date,'OC',false,null,anyreference);

                end if;

            end if;

            end if;

        end loop;

    end if;

    RETURN true;

END;

$$;


ALTER FUNCTION public.accept_tactr_postlog(postid text, userid text) OWNER TO dev;

--
-- TOC entry 517 (class 1255 OID 64430)
-- Dependencies: 1714 15
-- Name: accno_for_cheque_enter(text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION accno_for_cheque_enter(postid text, chqid text) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	temcheque "DetailCheque"%ROWTYPE;
	onlypayment boolean ;
	accno text;

BEGIN
	onlypayment:=true;
	for temcheque in select * from "DetailCheque" where "DetailCheque"."ChequeNo"=chqid and "DetailCheque"."PostID"=postid loop
		if temcheque."TypePay" <> 1 then
			onlypayment := false;
		end if;
	end loop;
	if onlypayment = true then
		select into accno bankofcompany."accno" from bankofcompany where bankofcompany.payment=true;
	else
		select into accno bankofcompany."accno" from bankofcompany where bankofcompany.payment=false;
	end if;
	return accno;
END;
$$;


ALTER FUNCTION public.accno_for_cheque_enter(postid text, chqid text) OWNER TO dev;

--
-- TOC entry 518 (class 1255 OID 64431)
-- Dependencies: 1714 15
-- Name: amt_before_vat(double precision); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION amt_before_vat(amtin double precision) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
	amt double precision;
BEGIN
	amt := round(cast(amtin*100/107 as numeric),2);
	return amt;
END;
$$;


ALTER FUNCTION public.amt_before_vat(amtin double precision) OWNER TO dev;

--
-- TOC entry 519 (class 1255 OID 64432)
-- Dependencies: 15 1714
-- Name: asset_name(integer, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION asset_name(assettype integer, assetid text) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	assetinfo text;

BEGIN
	if assettype=1 then
		select into assetinfo "Fc"."C_CARNAME" from "Fc" where "Fc"."CarID"=assetid;
	elsif assettype=2 then
		select into assetinfo "FGas"."gas_name" from "FGas" where "FGas"."GasID"=assetid;
	end if;

	RETURN assetinfo;
END;
$$;


ALTER FUNCTION public.asset_name(assettype integer, assetid text) OWNER TO dev;

--
-- TOC entry 520 (class 1255 OID 64433)
-- Dependencies: 1714 15
-- Name: asset_regis(integer, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION asset_regis(assettype integer, assetid text) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	assetinfo text;

BEGIN
	if assettype=1 then
		select into assetinfo "Fc"."C_REGIS" from "Fc" where "Fc"."CarID"=assetid;
	elsif assettype=2 then
		select into assetinfo "FGas"."car_regis" from "FGas" where "FGas"."GasID"=assetid;
	end if;

	RETURN assetinfo;
END;
$$;


ALTER FUNCTION public.asset_regis(assettype integer, assetid text) OWNER TO dev;

--
-- TOC entry 521 (class 1255 OID 64434)
-- Dependencies: 15 1714
-- Name: bank_code(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION bank_code(bankno text) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
	bankcode text;
BEGIN
	select into bankcode "BankCheque"."BankCode" from "BankCheque" where "BankNo"=bankno;
	return bankcode;
END;
$$;


ALTER FUNCTION public.bank_code(bankno text) OWNER TO dev;

--
-- TOC entry 522 (class 1255 OID 64435)
-- Dependencies: 1714 15
-- Name: c_date_number(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION c_date_number(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
    msg text:='';
BEGIN
    msg := to_char(datein,'DD-MM') || '-' || EXTRACT(YEAR FROM datein)+543;
    return  msg;
END;
$$;


ALTER FUNCTION public.c_date_number(datein date) OWNER TO dev;

--
-- TOC entry 523 (class 1255 OID 64436)
-- Dependencies: 15 1714
-- Name: c_datethai(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION c_datethai(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
    dd integer;
    mm integer;
    yy integer;
    thaitextmonth text;
    msg text:='';
BEGIN
    dd:=EXTRACT(DAY FROM datein);
    mm:=EXTRACT(MONTH FROM datein);
    yy:=EXTRACT(YEAR FROM datein);

    if mm=1 then
        thaitextmonth := 'ม.ค.';
    elsif mm=2 then
        thaitextmonth := 'ก.พ.';
    elsif mm=3 then
        thaitextmonth := 'มี.ค.';
    elsif mm=4 then
        thaitextmonth := 'เม.ย.';
    elsif mm=5 then
        thaitextmonth := 'พ.ค.';
    elsif mm=6 then
        thaitextmonth := 'มิ.ย.';
    elsif mm=7 then
        thaitextmonth := 'ก.ค.';
    elsif mm=8 then
        thaitextmonth := 'ส.ค.';
    elsif mm=9 then
        thaitextmonth := 'ก.ย.';
    elsif mm=10 then
        thaitextmonth := 'ต.ค.';
    elsif mm=11 then
        thaitextmonth := 'พ.ย.';
    elsif mm=12 then
        thaitextmonth := 'ธ.ค.';
    end if;

    msg := to_char(dd, '99 ') || thaitextmonth || ''|| to_char(yy+543,'9999');

    return  msg;
END;
$$;


ALTER FUNCTION public.c_datethai(datein date) OWNER TO dev;

--
-- TOC entry 524 (class 1255 OID 64437)
-- Dependencies: 15 1714
-- Name: cancel_receipt(text, text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION cancel_receipt(recno text, memo text, return_to text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfr "Fr"%ROWTYPE;
	temfotherpay "FOtherpay"%ROWTYPE;
	cancelno text;
	deposit_no text;
BEGIN
	cancelno := gen_c_no(current_date);
	if substring(recno from 3 for 1)='R' then
		select into temfr * from "Fr" where "R_Receipt"=recno;
		if return_to = '' then
			deposit_no := gen_k_no(current_date);
			insert into "CancelReceipt" values (cancelno,temfr."IDNO",current_date,-temfr."R_Money",temfr."R_Prndate",temfr."R_Date",temfr."R_Receipt",temfr."PayType",deposit_no);
			insert into "FOtherpay" values (temfr."IDNO",current_date,deposit_no,temfr."R_Money",200,temfr."R_Bank",current_date,temfr."PayType",false,'','');
		else 
			insert into "CancelReceipt" values (cancelno,temfr."IDNO",current_date,-temfr."R_Money",temfr."R_Prndate",temfr."R_Date",temfr."R_Receipt",temfr."PayType",return_to);
		end if;
		update "Fr" set "Cancel"=true, "R_memo"=memo where "R_Receipt"=recno;
	else 
		select into temfotherpay * from "FOtherpay" where "O_RECEIPT"=recno;
		if return_to = '' then
			deposit_no := gen_k_no(current_date);
			insert into "CancelReceipt" values (cancelno,temfotherpay."IDNO",current_date,-temfotherpay."O_MONEY",temfotherpay."O_PRNDATE",temfotherpay."O_DATE",temfotherpay."O_RECEIPT",temfotherpay."PayType",deposit_no);
			insert into "FOtherpay" values (temfotherpay."IDNO",current_date,deposit_no,temfotherpay."O_MONEY",200,temfotherpay."O_BANK",current_date,temfotherpay."PayType",false,'','');
		else
			insert into "CancelReceipt" values (cancelno,temfotherpay."IDNO",current_date,-temfotherpay."O_MONEY",temfotherpay."O_PRNDATE",temfotherpay."O_DATE",temfotherpay."O_RECEIPT",return_to);
		end if;
		
		update "FOtherpay" set "Cancel"=true, "O_memo"=memo where "O_RECEIPT"=recno;
	end if;
	RETURN true;
END;
$$;


ALTER FUNCTION public.cancel_receipt(recno text, memo text, return_to text) OWNER TO dev;

--
-- TOC entry 525 (class 1255 OID 64438)
-- Dependencies: 15 1714
-- Name: cancel_vat_receipt(text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION cancel_vat_receipt(recno text, memo text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE

BEGIN
	update "FVat" set "Cancel"=true, "V_memo"=memo ,"VatValue"=0 where "V_Receipt"=recno;
	
	RETURN true;
END;
$$;


ALTER FUNCTION public.cancel_vat_receipt(recno text, memo text) OWNER TO dev;

--
-- TOC entry 526 (class 1255 OID 64439)
-- Dependencies: 15 1714
-- Name: check_cus_name(text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION check_cus_name(fa_name text, fa_surname text) RETURNS character varying
    LANGUAGE plpgsql
    AS $$DECLARE
	temname text;
	temsurname text;
	cusid varchar(12);
BEGIN
	temname:=trim(both ' ' from fa_name);
	temsurname:=trim(both ' ' from fa_surname);
	select into  cusid  "Fa1"."CusID" from "Fa1" where trim(both ' ' from "Fa1"."A_NAME")=temname and trim(both ' ' from "Fa1"."A_SIRNAME")=temsurname;
	if found then
		return trim(both ' ' from cusid);
	else 
		return '';
	end if;
END;
$$;


ALTER FUNCTION public.check_cus_name(fa_name text, fa_surname text) OWNER TO dev;

--
-- TOC entry 527 (class 1255 OID 64440)
-- Dependencies: 1714 15
-- Name: check_not_sent_vat(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION check_not_sent_vat(idno text) RETURNS integer
    LANGUAGE plpgsql
    AS $$DECLARE
	temaccpayment "VAccPayment"%ROWTYPE;
	countrec integer;
	nonotsent integer;
	temdate text:='';
	
	mm integer;
	yy integer;

BEGIN
	mm:=EXTRACT(MONTH FROM current_date);
	yy:=EXTRACT(YEAR FROM current_date);
	
	select count(*) into countrec from "VAccPayment" where "IDNO"=idno;
	if countrec > 0 then
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-01' ;
		select count(*)  into nonotsent from "VAccPayment" where "IDNO"=idno and "DueDate"<to_date(temdate,'YYYY-MM-DD') and "V_Date" is null;
		return nonotsent;
	else 
		return -1;  --- it is not create accpayment ---
	end if;
END;
$$;


ALTER FUNCTION public.check_not_sent_vat(idno text) OWNER TO dev;

--
-- TOC entry 528 (class 1255 OID 64441)
-- Dependencies: 1714 15
-- Name: check_this_id_transfer(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION check_this_id_transfer(idno text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	rec_count integer;
BEGIN
	select count(*) into rec_count from "Fp" where "P_TransferIDNO"=idno;
	if rec_count <> 0
	then return true;
	else return false;
	end if;
END;
$$;


ALTER FUNCTION public.check_this_id_transfer(idno text) OWNER TO dev;

--
-- TOC entry 529 (class 1255 OID 64442)
-- Dependencies: 15 1714
-- Name: check_vat_use_recdate(text, integer, date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION check_vat_use_recdate(idno text, timepayment integer, recdate date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE


-----------------------------------------
--------- input parameter ---------------
-----------------------------------------
-- idno is id of customer payment
-- timepayment time of payment to pay
-- recdate date of receipt which need used
-----------------------------------------
	
	temaccpayment "VAccPayment"%ROWTYPE;
	counttime integer;
	pass boolean;
	
BEGIN
	counttime:=0;
	pass:=true;

	for temaccpayment in select * from "VAccPayment" where "IDNO" = idno and "R_Date" is null loop
	
		
		if counttime=timepayment then 
			exit;
		else
			if temaccpayment."V_Date" is null then
				pass:=true;
				exit;
			else
				if temaccpayment."V_Date" <= recdate then
					pass:=true;
				else
					pass:= false;
					exit;
				end if;
			end if;
		end if;
		counttime:= counttime+1;
		
	end loop;
	return pass;

END;
$$;


ALTER FUNCTION public.check_vat_use_recdate(idno text, timepayment integer, recdate date) OWNER TO dev;

--
-- TOC entry 530 (class 1255 OID 64443)
-- Dependencies: 1714 15
-- Name: conversiondatetothaitext(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION conversiondatetothaitext(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
	dd integer;
	mm integer;
	yy integer;
	thaitextmonth text;
	msg text:='';
BEGIN
	dd:=EXTRACT(DAY FROM datein);
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	if mm=1 then
		thaitextmonth := 'มกราคม';
	elsif mm=2 then
		thaitextmonth := 'กุมภาพันธ์';
	elsif mm=3 then
		thaitextmonth := 'มีนาคม';
	elsif mm=4 then
		thaitextmonth := 'เมษายน';
	elsif mm=5 then
		thaitextmonth := 'พฤษภาคม';
	elsif mm=6 then
		thaitextmonth := 'มิถุนายน';
	elsif mm=7 then
		thaitextmonth := 'กรกฎาคม';
	elsif mm=8 then
		thaitextmonth := 'สิงหาคม';
	elsif mm=9 then
		thaitextmonth := 'กันยายน';
	elsif mm=10 then
		thaitextmonth := 'ตุลาคม';
	elsif mm=11 then
		thaitextmonth := 'พฤศจิกายน';
	elsif mm=12 then
		thaitextmonth := 'ธันวาคม';
	end if;

	msg := to_char(dd, '99 ') || thaitextmonth || ' พ.ศ.'|| to_char(yy+543,'9999');

	return  msg;
END;
$$;


ALTER FUNCTION public.conversiondatetothaitext(datein date) OWNER TO dev;

--
-- TOC entry 531 (class 1255 OID 64444)
-- Dependencies: 1714 15
-- Name: conversionnumtothaitext(double precision); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION conversionnumtothaitext(numin double precision) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
	numtext text;
	thaitext text;
	thaiposition text;
	msg text :='';
	numtothai char;
BEGIN
	numtext := to_char(numin,'FM999999999999V99');
	For i IN 1..length(numtext) LOOP
		if i=1 then
			thaiposition:='สตางค์';
		elsif i=2 then
			thaiposition:='สิบ';
		elsif i=3 then
			thaiposition:='บาท';
		elsif (i=4 or i=10) then
			thaiposition:='สิบ';
		elsif (i=5 or i=11) then
			thaiposition:='ร้อย';
		elsif (i=6 or i=12) then
			thaiposition:='พัน';
		elsif (i=7 or i=13) then
			thaiposition:='หมื่น';
		elsif (i=8 or i=14) then
			thaiposition:='แสน';
		elsif i=9 then
			thaiposition:='ล้าน';
		end if;

		if (i=2) and (substring(numtext from length(numtext)-i+1 for 1)='0') then
			thaiposition :='';
		end if;
		
		if  ((i>3 and i<9) and (substring(numtext from length(numtext)-i+1 for 1)='0')) then
			thaiposition:='';
		else   
			if  ((i>9) and (substring(numtext from length(numtext)-i+1 for 1)='0')) then
				thaiposition:='';
			end if;			
		end if;

		msg:=thaiposition || msg;
		
		numtothai:=substring(numtext from length(numtext)-i+1 for 1);
		if numtothai='1' then
			thaitext:='หนึ่ง';
		elsif numtothai='2' then
			thaitext:='สอง';
		elsif numtothai='3' then
			thaitext:='สาม';
		elsif numtothai='4' then
			thaitext:='สี่';		
		elsif numtothai='5' then
			thaitext:='ห้า';
		elsif numtothai='6'  then
			thaitext:='หก';
		elsif numtothai='7'  then
			thaitext:='เจ็ด';
		elsif numtothai='8'  then
			thaitext:='แปด';
		elsif numtothai='9' then
			thaitext:='เก้า';
		else thaitext:='';
		end if;

		if (i=2) and (substring(numtext from length(numtext)-1 for 2)='00') then
			msg:='';
		end if;
		
		if numtothai='1' 
		then 
		    if (i=1 or i=3 or i=9) and length(numtext)>i
			then thaitext:='เอ็ด';
		    else 
			if (i=2 or i=4 or i=10)  
				then thaitext:='';
		        end if;
		    end if;
		end if;
		if numtothai='2' and (i=2 or i=4 or i=10)
		then
		    thaitext := 'ยี่';
		end if;
		msg :=  thaitext || msg;
		
	END LOOP;
	return  msg ;
END;
$$;


ALTER FUNCTION public.conversionnumtothaitext(numin double precision) OWNER TO dev;

--
-- TOC entry 532 (class 1255 OID 64445)
-- Dependencies: 1714 15
-- Name: cost_of_car_today(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION cost_of_car_today(idno text) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
	temremine double precision;
	costvat double precision;
BEGIN
	select into temremine "Remine" from "VAccPayment" 
	where "IDNO"=idno and "DueNo" in (select count(*) from "VAccPayment" where "R_Receipt" is not null and "IDNO"=idno);

	select into costvat sum("VatValue") from "VAccPayment" 
	where "R_Receipt" is null and "V_Receipt" is not null and "IDNO" = idno;
	
	return temremine+costvat;
END;
$$;


ALTER FUNCTION public.cost_of_car_today(idno text) OWNER TO dev;

--
-- TOC entry 533 (class 1255 OID 64446)
-- Dependencies: 1714 15
-- Name: customer_name(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION customer_name(cusid text) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	prename text;
	firstname text;
	lastname text;

BEGIN
	select into prename, firstname, lastname "Fa1"."A_FIRNAME", "Fa1"."A_NAME", "Fa1"."A_SIRNAME" from "Fa1" 
	where "Fa1"."CusID"=cusid;
	RETURN btrim(prename) || ' ' ||btrim(firstname) || ' ' || btrim(lastname);
END;
$$;


ALTER FUNCTION public.customer_name(cusid text) OWNER TO dev;

--
-- TOC entry 534 (class 1255 OID 64447)
-- Dependencies: 15 1714
-- Name: deposit_balance(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION deposit_balance(idno text) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
	amt double precision;
BEGIN
	select sum("FOtherpay"."O_MONEY") into amt from "FOtherpay" where "FOtherpay"."IDNO"=idno and ("FOtherpay"."O_Type">=200 and "FOtherpay"."O_Type"<300) and "FOtherpay"."Cancel"=False;
	return amt;
END;
$$;


ALTER FUNCTION public.deposit_balance(idno text) OWNER TO dev;

--
-- TOC entry 535 (class 1255 OID 64448)
-- Dependencies: 1714 15
-- Name: discount_close(date, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION discount_close(closedate date, idno text) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
	waitincome double precision;
	discount double precision;

BEGIN
	select into waitincome "VCusPayment"."WaitIncome" from "VCusPayment" 
	where "IDNO" = idno and "DueDate">closedate and "R_Date" is null ;
	if found then
		discount:=waitincome/2;
	else
		return 0;
	end if;
	RETURN discount;
END;
$$;


ALTER FUNCTION public.discount_close(closedate date, idno text) OWNER TO dev;

--
-- TOC entry 536 (class 1255 OID 64449)
-- Dependencies: 15 1714
-- Name: gen_c_no(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_c_no(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	dd integer;
	mm integer;
	yy integer;
	
	tempos integer;
	temdate text:='';
	outputmsg text:='';
BEGIN
	dd:=EXTRACT(DAY FROM datein);
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	select into tempos  "FReceiptNO"."C"
	from 	public."FReceiptNO" Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);

	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
		insert into "FReceiptNO" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0,0,0);
		tempos:=0;
	end if;
	
	update "FReceiptNO" set  "C"="C"+1 Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);
	outputmsg :=substring(to_char(yy,'9999') from 4 for 2) || 'C'|| to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(tempos+1,'FM009');
	RETURN outputmsg;
END;
$$;


ALTER FUNCTION public.gen_c_no(datein date) OWNER TO dev;

--
-- TOC entry 537 (class 1255 OID 64450)
-- Dependencies: 1714 15
-- Name: gen_decode_ref1(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_decode_ref1(idno text) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	before_encode text:='';
	numberincode integer;
	result text:='';
BEGIN
	For i IN 1..length(idno) LOOP	
		numberincode := 9-to_number(substring(idno from i for 1),'0');
		before_encode := before_encode||trim(both ' ' from to_char(numberincode,'0'));	
	END LOOP;

	result := substring(before_encode from 1 for 1) || 		  
		  substring(before_encode from 2 for 1) || '-' ||
		  substring(before_encode from 4 for 1) || 
		  substring(before_encode from 9 for 1) || '-' ||
		  substring(before_encode from 5 for 1) || 
		  substring(before_encode from 8 for 1) || 
		  substring(before_encode from 7 for 1) || 
		  substring(before_encode from 6 for 1) ||
		  substring(before_encode from 3 for 1) ;
		  
	RETURN result;
END;
$$;


ALTER FUNCTION public.gen_decode_ref1(idno text) OWNER TO dev;

--
-- TOC entry 538 (class 1255 OID 64451)
-- Dependencies: 15 1714
-- Name: gen_encode_ref1(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_encode_ref1(idno text) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE

	before_encode text:='';
	numberincode integer;
	result text:='';

BEGIN
	-- FORMAT BEFORE 2012
	--	114-01001 (9)
	-- FORMAT AFTER 2012
	--	12-010001T-BK01 (15)
	
if length(idno)=9 then -- ถ้ามี 9 หลัก เป็น FORMAT ก่อนปี 2012

	For i IN 1..length(idno) LOOP	

		if substring(idno from i for 1) <> '-' then
		        numberincode := 9-to_number(substring(idno from i for 1),'0');
			before_encode := before_encode||trim(both ' ' from to_char(numberincode,'0'));
		end if;
	END LOOP;

	result := substring(before_encode from 1 for 1) || 		  
		  substring(before_encode from 2 for 1) ||
		  substring(before_encode from 9 for 1) || 
		  substring(before_encode from 3 for 1) || 
		  substring(before_encode from 5 for 1) || 
		  substring(before_encode from 8 for 1) || 
		  substring(before_encode from 7 for 1) || 
		  substring(before_encode from 6 for 1) ||
		  substring(before_encode from 4 for 1) ;
	RETURN result;

else	-- ถ้าเป็น Format หลัง 2012 แบบ 15 หลัก
	For i IN 1..length(idno) LOOP
	
		if substring(idno from i for 1) <> '-' AND (
			substring(idno from i for 1) = '0' OR
			substring(idno from i for 1) = '1' OR
			substring(idno from i for 1) = '2' OR
			substring(idno from i for 1) = '3' OR
			substring(idno from i for 1) = '4' OR
			substring(idno from i for 1) = '5' OR
			substring(idno from i for 1) = '6' OR
			substring(idno from i for 1) = '7' OR
			substring(idno from i for 1) = '8' OR
			substring(idno from i for 1) = '9' ) then
		        numberincode := 9-to_number(substring(idno from i for 1),'0');
			before_encode := before_encode||trim(both ' ' from to_char(numberincode,'0'));
		else
			if i=10 AND substring(idno from i for 1)='T' then
				before_encode := before_encode||'1';
			end if;

			if i=13 AND substring(idno from i for 1)='K' AND substring(idno from i-1 for 1)='B' then
				before_encode := before_encode||'01';
			end if;
		end if;

	END LOOP;

	result := substring(before_encode from 10 for 1) || 
		substring(before_encode from 11 for 1) || 
		substring(before_encode from 12 for 1) || 
		substring(before_encode from 13 for 1) || 
		substring(before_encode from 9 for 1) || 
		substring(before_encode from 1 for 1) || 
		substring(before_encode from 2 for 1) || 
		substring(before_encode from 5 for 1) || 
		substring(before_encode from 6 for 1) || 
		substring(before_encode from 7 for 1) || 
		substring(before_encode from 8 for 1) || 
		substring(before_encode from 3 for 1) ||
		substring(before_encode from 4 for 1);

	RETURN result;
end if;

END;
$$;


ALTER FUNCTION public.gen_encode_ref1(idno text) OWNER TO dev;

--
-- TOC entry 539 (class 1255 OID 64452)
-- Dependencies: 1714 15
-- Name: gen_encode_ref1_old(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_encode_ref1_old(idno text) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	before_encode text:='';
	numberincode integer;
	result text:='';
BEGIN
	For i IN 1..length(idno) LOOP	
		if substring(idno from i for 1) <> '-' then
		        numberincode := 9-to_number(substring(idno from i for 1),'0');
			before_encode := before_encode||trim(both ' ' from to_char(numberincode,'0'));
		end if;
	END LOOP;

	result := substring(before_encode from 1 for 1) || 		  
		  substring(before_encode from 2 for 1) ||
		  substring(before_encode from 9 for 1) || 
		  substring(before_encode from 3 for 1) || 
		  substring(before_encode from 5 for 1) || 
		  substring(before_encode from 8 for 1) || 
		  substring(before_encode from 7 for 1) || 
		  substring(before_encode from 6 for 1) ||
		  substring(before_encode from 4 for 1) ;
		  
	RETURN result;
END;
$$;


ALTER FUNCTION public.gen_encode_ref1_old(idno text) OWNER TO dev;

--
-- TOC entry 540 (class 1255 OID 64453)
-- Dependencies: 1714 15
-- Name: gen_k_no(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_k_no(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	dd integer;
	mm integer;
	yy integer;
	
	temk integer;
	temdate text:='';
	outputmsg text:='';
BEGIN
	dd:=EXTRACT(DAY FROM datein);
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	select into temk  "FReceiptNO"."K"
	from 	public."FReceiptNO" Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);

	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
		insert into "FReceiptNO" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0,0,0,0);
		temk:=0;
	end if;
	
	update "FReceiptNO" set  "K"="K"+1 Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);
	outputmsg :=substring(to_char(yy,'9999') from 4 for 2) || 'K'|| to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temk+1,'FM009');
	RETURN outputmsg;
END;
$$;


ALTER FUNCTION public.gen_k_no(datein date) OWNER TO dev;

--
-- TOC entry 541 (class 1255 OID 64454)
-- Dependencies: 15 1714
-- Name: gen_n_no(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_n_no(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	dd integer;
	mm integer;
	yy integer;
	
	temn integer;
	temdate text:='';
	outputmsg text:='';
BEGIN
	dd:=EXTRACT(DAY FROM datein);
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	select into temn  "FReceiptNO"."N"
	from 	public."FReceiptNO" Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);

	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
		insert into "FReceiptNO" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0,0,0,0);
		temn:=0;
	end if;
	
	update "FReceiptNO" set  "N"="N"+1 Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);
	outputmsg :=substring(to_char(yy,'9999') from 4 for 2) || 'N'|| to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temn+1,'FM009');
	RETURN outputmsg;
END;
$$;


ALTER FUNCTION public.gen_n_no(datein date) OWNER TO dev;

--
-- TOC entry 542 (class 1255 OID 64455)
-- Dependencies: 1714 15
-- Name: gen_pos_no(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_pos_no(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	dd integer;
	mm integer;
	yy integer;
	
	tempos integer;
	temdate text:='';
	outputmsg text:='';
BEGIN
	dd:=EXTRACT(DAY FROM datein);
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	select into tempos  "FReceiptNO"."P"
	from 	public."FReceiptNO" Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);

	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
		insert into "FReceiptNO" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0,0,0,0);
		tempos:=0;
	end if;
	
	update "FReceiptNO" set  "P"="P"+1 Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);
	outputmsg :=substring(to_char(yy,'9999') from 4 for 2) || 'P'|| to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(tempos+1,'FM009');
	RETURN outputmsg;
END;
$$;


ALTER FUNCTION public.gen_pos_no(datein date) OWNER TO dev;

--
-- TOC entry 543 (class 1255 OID 64456)
-- Dependencies: 1714 15
-- Name: gen_rec_no(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_rec_no(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	dd integer;
	mm integer;
	yy integer;
	
	temr integer;
	temdate text:='';
	outputmsg text:='';
BEGIN
	dd:=EXTRACT(DAY FROM datein);
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	select into temr  "FReceiptNO"."R"
	from 	public."FReceiptNO" Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);

	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
		insert into "FReceiptNO" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0,0,0,0);
		temr:=0;
	end if;
	
	update "FReceiptNO" set  "R"="R"+1 Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);
	outputmsg :=substring(to_char(yy,'9999') from 4 for 2) || 'R'|| to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temr+1,'FM009');
	RETURN outputmsg;
END;
$$;


ALTER FUNCTION public.gen_rec_no(datein date) OWNER TO dev;

--
-- TOC entry 544 (class 1255 OID 64457)
-- Dependencies: 1714 15
-- Name: gen_vat_no(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_vat_no(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	dd integer;
	mm integer;
	yy integer;
	
	temv integer;
	temdate text:='';
	outputmsg text:='';
BEGIN
	dd:=EXTRACT(DAY FROM datein);
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	select into temv  "FReceiptNO"."V"
	from 	public."FReceiptNO" Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);

	if not found  then	
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(dd,'99');
		insert into "FReceiptNO" values (to_date(temdate,'YYYY-MM-DD') ,0,0,0,0,0,0);
		temv:=0;
	end if;
	
	update "FReceiptNO" set  "V"="V"+1 Where (extract(month from "FReceiptNO"."Rec_date")=mm and extract(year from "FReceiptNO"."Rec_date")=yy and extract(day from "FReceiptNO"."Rec_date")=dd);
	outputmsg :=substring(to_char(yy,'9999') from 4 for 2) || 'V'|| to_char(mm,'FM09') || to_char(dd,'FM09') || to_char(temv+1,'FM009');
	RETURN outputmsg;
END;
$$;


ALTER FUNCTION public.gen_vat_no(datein date) OWNER TO dev;

--
-- TOC entry 545 (class 1255 OID 64458)
-- Dependencies: 1714 15
-- Name: gen_vat_receipt(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION gen_vat_receipt(datein date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temaccpayment "VAccPayment"%ROWTYPE;
	temvatvalue double precision;
	stopvatstate boolean;
	acclose boolean;
	stopvatdate date;
BEGIN
	for temaccpayment in select * from "VAccPayment" where "DueDate" = datein and "V_Receipt" is null loop
		select into temvatvalue,stopvatdate, stopvatstate,acclose  "Fp"."P_VAT","Fp"."P_StopVatDate","Fp"."P_StopVat","Fp"."P_ACCLOSE" from "Fp" 
		where "Fp"."IDNO"=temaccpayment."IDNO";
		if (stopvatstate=false or stopvatdate>datein) and (acclose=false) then
			insert into "FVat" values (temaccpayment."IDNO",temaccpayment."DueNo",gen_vat_no(datein),datein,temvatvalue,current_date,False,False,null);
		end if;
	end loop;
	return true;
END;
$$;


ALTER FUNCTION public.gen_vat_receipt(datein date) OWNER TO dev;

--
-- TOC entry 546 (class 1255 OID 64459)
-- Dependencies: 1714 15
-- Name: generate_cash_id(date, integer, integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION generate_cash_id(datein date, brnid integer, knd integer) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	yy integer;
	c_car_id integer;
	c_gas_id integer;
	temid text:='';

	-- ชื่อย่อของสาขาแบบตัวอักษรเช่น brnid = 1 => BK01 (Nawamin)
	brntext text := '';
	
	-- ชื่อย่อประเภทรหัส เช่น knd = 1 => T (Taxi)
	kndtext text := '';

BEGIN

-- parameter with sent in function
-- datein  use month and year which customer buy product
-- brnid is branchid 1=navamin, 2=jaran
-- knd is kind of asset 1=car , 5=gas 

	yy:=EXTRACT(YEAR FROM datein);

	select into  c_car_id , c_gas_id "ContactCashID"."car_id","ContactCashID"."gas_id" from "ContactCashID" Where "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;

	if not found  then	
		insert into "ContactCashID" values (yy,brnid,0,0,0);
		c_car_id :=0;
		c_gas_id :=0;
	end if;

	-- todo: แก้ให้รองรับสาขาอื่นๆ
	brntext := 'BK01';

	if knd = 1 then
		kndtext := 'T';
		update "ContactCashID" set car_id = c_car_id+1 where  "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;
		if substring(trim(to_char(yy,'9999')) from 1 for 4)='2011' then -- ถ้าเป็นปี 2011 ให้ใช้แบบเก่าไปก่อน
			temid :=to_char(knd,'FM9')  || to_char(brnid,'FM99') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-14' || to_char(c_car_id+1,'FM009');
		else -- หลังปี 2011 ใช้แบบใหม่ได้
			temid := substring(trim(to_char(yy,'9999')) from 3 for 2) || '-14' || to_char(c_car_id+1,'FM0009') || kndtext || '-' || brntext;
		end if;
	else
		-- todo: แบ่งประเภทมากขึ้น
		kndtext := 'G'; -- Gas
		update "ContactCashID" set gas_id = c_gas_id+1 where  "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;

		if substring(trim(to_char(yy,'9999')) from 1 for 4)='2011' then -- ถ้าเป็นปี 2011 ให้ใช้แบบเก่าไปก่อน
			temid :=to_char(knd,'FM9')  || to_char(brnid,'FM99') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-14' || to_char(c_gas_id+1,'FM009');
		else -- หลังปี 2011 ใช้แบบใหม่ได้
			temid := substring(trim(to_char(yy,'9999')) from 3 for 2) || '-14' || to_char(c_gas_id+1,'FM0009') || kndtext || '-' || brntext;
		end if;
	end if;

	RETURN temid;


END;
$$;


ALTER FUNCTION public.generate_cash_id(datein date, brnid integer, knd integer) OWNER TO dev;

--
-- TOC entry 547 (class 1255 OID 64460)
-- Dependencies: 1714 15
-- Name: generate_cash_id_old(date, integer, integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION generate_cash_id_old(datein date, brnid integer, knd integer) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	yy integer;
	c_car_id integer;
	c_gas_id integer;
	temid text:='';
BEGIN

-- parameter with sent in function
-- datein  use month and year which customer buy product
-- brnid is branchid 1=navamin, 2=jaran
-- knd is kind of asset 1=car , 5=gas 

	yy:=EXTRACT(YEAR FROM datein);

	select into  c_car_id , c_gas_id "ContactCashID"."car_id","ContactCashID"."gas_id" from "ContactCashID" Where "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;
	if not found  then	
		insert into "ContactCashID" values (yy,brnid,0,0,0);
		c_car_id :=0;
		c_gas_id :=0;
	end if;
	if knd = 1 then
		update "ContactCashID" set car_id = c_car_id+1 where  "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;
		temid :=to_char(knd,'FM9')  || to_char(brnid,'FM99') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-14' || to_char(c_car_id+1,'FM009');
	else
		update "ContactCashID" set gas_id = c_gas_id+1 where  "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;
		temid :=to_char(knd,'FM9')  || to_char(brnid,'FM99') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-14' || to_char(c_gas_id+1,'FM009');
	end if;
	RETURN temid;
END;
$$;


ALTER FUNCTION public.generate_cash_id_old(datein date, brnid integer, knd integer) OWNER TO dev;

--
-- TOC entry 548 (class 1255 OID 64461)
-- Dependencies: 15 1714
-- Name: generate_id(date, integer, integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION generate_id(datein date, brnid integer, knd integer) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE


	mm integer;
	yy integer;


	tem_carid integer;

	tem_gasid integer;

	tem_ntid integer;

	tem_outcusid integer;

	temid text:='';

	temdate text := '';

	-- ชื่อย่อของสาขาแบบตัวอักษรเช่น brnid = 1 => BK01 (Nawamin)
	brntext text := '';

	-- ชื่อย่อประเภทรหัส เช่น knd = 1 => T (Taxi)
	kndtext text := '';

BEGIN

-- parameter with sent in function


-- datein  use month and year which customer buy product


-- brnid is branchid 1=navamin, 2=jaran


-- knd is kind of asset 1=car , 5=gas ,3=NT , 4=out customer


	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);


	select into tem_carid, tem_gasid, tem_ntid ,tem_outcusid "ContactID"."carid", "ContactID"."gasid", "ContactID"."ntid","ContactID"."outcusid"
	from 	public."ContactID" Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid);

	if not found  then	


		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(1,'99');


		insert into "ContactID" values (brnid,to_date(temdate,'YYYY-MM-DD') ,0,0,0,0);


		tem_carid:=0;


		tem_gasid:=0;


		tem_ntid:=0;


		tem_outcusid:=0;


	end if;


	if knd=1 then

		kndtext:='T'; --Taxi

		if brnid=1 then
			brntext:='BK01'; --Nawamin branch
		end if;

		update "ContactID" set  carid=tem_carid+1 Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid); 
		
		if substring(trim(to_char(yy,'9999')) from 1 for 4)='2011' then -- ถ้าเป็นปี 2011 ให้ใช้แบบเก่าไปก่อน
			temid :=to_char(knd,'FM99') || to_char(brnid,'FM9') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-'|| to_char(mm,'FM09') || to_char(tem_carid+1,'FM009');
		else -- หลังปี 2011 ใช้แบบใหม่ได้
			temid := substring(trim(to_char(yy,'9999')) from 3 for 2) || '-'|| to_char(mm,'FM09') || to_char(tem_carid+1,'FM0009') || kndtext || '-' || brntext;
		end if;

	elsif knd=5 then

		kndtext:='G'; --Gas

		if brnid=1 then
			brntext:='BK01'; --Nawamin branch
		end if;

		update "ContactID" set  gasid=tem_gasid+1 Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid); 

		if substring(trim(to_char(yy,'9999')) from 1 for 4)='2011' then -- ถ้าเป็นปี 2011 ให้ใช้แบบเก่าไปก่อน
			temid :=to_char(knd,'FM99') || to_char(brnid,'FM9') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-'|| to_char(mm,'FM09') || to_char(tem_gasid+1,'FM009');
		else -- หลังปี 2011 ใช้แบบใหม่ได้
			temid := substring(trim(to_char(yy,'9999')) from 3 for 2) || '-'|| to_char(mm,'FM09') || to_char(tem_gasid+1,'FM0009') || kndtext || '-' || brntext;
		end if;

	elsif knd=3 then


		update "ContactID" set  ntid=tem_ntid+1 Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid); 


		temid :='NT'||to_char(brnid,'FM99') ||  substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(tem_ntid+1,'FM009');


	elsif knd=4 then


		update "ContactID" set  outcusid=tem_outcusid+1 Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid); 


		temid := '0'|| to_char(brnid,'FM9') || substring(trim(to_char(yy,'9999')) from 4 for 1) || '-'|| to_char(mm,'FM09') || to_char(tem_outcusid+1,'FM009');


	end if;


	RETURN temid;


END;


$$;


ALTER FUNCTION public.generate_id(datein date, brnid integer, knd integer) OWNER TO dev;

--
-- TOC entry 549 (class 1255 OID 64462)
-- Dependencies: 15 1714
-- Name: generate_id_old(date, integer, integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION generate_id_old(datein date, brnid integer, knd integer) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE


	mm integer;
	yy integer;


	tem_carid integer;

	tem_gasid integer;

	tem_ntid integer;

	tem_outcusid integer;

	temid text:='';

	temdate text := '';

	-- ชื่อย่อของสาขาแบบตัวอักษรเช่น brnid = 1 => NW (Nawamin)
	brntext text := '';

	-- ชื่อย่อประเภทรหัส เช่น knd = 1 => T (Taxi)
	kndtext text := '';

BEGIN

-- parameter with sent in function


-- datein  use month and year which customer buy product


-- brnid is branchid 1=navamin, 2=jaran


-- knd is kind of asset 1=car , 5=gas ,3=NT , 4=out customer


	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);


	select into tem_carid, tem_gasid, tem_ntid ,tem_outcusid "ContactID"."carid", "ContactID"."gasid", "ContactID"."ntid","ContactID"."outcusid"
	from 	public."ContactID" Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid);

	if not found  then	


		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-' || to_char(1,'99');


		insert into "ContactID" values (brnid,to_date(temdate,'YYYY-MM-DD') ,0,0,0,0);


		tem_carid:=0;


		tem_gasid:=0;


		tem_ntid:=0;


		tem_outcusid:=0;


	end if;


	if knd=1 then

		kndtext:='T'; --Taxi

		if brnid=1 then
			brntext:='NW'; --Nawamin branch
		end if;

		update "ContactID" set  carid=tem_carid+1 Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid); 

		-- temid :=to_char(knd,'FM99') || to_char(brnid,'FM9') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-'|| to_char(mm,'FM09') || to_char(tem_carid+1,'FM009');
		temid := substring(trim(to_char(yy,'9999')) from 4 for 2) || '-'|| to_char(mm,'FM09') || to_char(tem_carid+1,'FM0009') || kndtext || brntext;


	elsif knd=5 then

		kndtext:='G'; --Gas

		if brnid=1 then
			brntext:='NW'; --Nawamin branch
		end if;

		update "ContactID" set  gasid=tem_gasid+1 Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid); 


		--temid :=to_char(knd,'FM99') || to_char(brnid,'FM9') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-'|| to_char(mm,'FM09') || to_char(tem_gasid+1,'FM009');
		temid := substring(trim(to_char(yy,'9999')) from 4 for 2) || '-'|| to_char(mm,'FM09') || to_char(tem_gasid+1,'FM0009') || kndtext || brntext;


	elsif knd=3 then


		update "ContactID" set  ntid=tem_ntid+1 Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid); 


		temid :='NT'||to_char(brnid,'FM99') ||  substring(to_char(yy,'9999') from 4 for 2) || to_char(mm,'FM09') || to_char(tem_ntid+1,'FM009');


	elsif knd=4 then


		update "ContactID" set  outcusid=tem_outcusid+1 Where (extract(month from "ContactID"."monthid")=mm and extract(year from "ContactID"."monthid")=yy and "ContactID"."branch"=brnid); 


		temid := '0'|| to_char(brnid,'FM9') || substring(trim(to_char(yy,'9999')) from 4 for 1) || '-'|| to_char(mm,'FM09') || to_char(tem_outcusid+1,'FM009');


	end if;


	RETURN temid;


END;


$$;


ALTER FUNCTION public.generate_id_old(datein date, brnid integer, knd integer) OWNER TO dev;

--
-- TOC entry 550 (class 1255 OID 64463)
-- Dependencies: 1714 15
-- Name: generate_tran_id(date, integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION generate_tran_id(datein date, brnid integer) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	yy integer;
	temtranid integer;
	temid text:='';

	-- ชื่อย่อของสาขาแบบตัวอักษรเช่น brnid = 1 => BK01 (Nawamin)
	brntext text := '';
	
	-- ชื่อย่อประเภทรหัส เช่น knd = 1 => T (Taxi)
	kndtext text := '';
	
BEGIN
	yy:=EXTRACT(YEAR FROM datein);

	select into  temtranid "ContactCashID"."tran_id" from "ContactCashID" Where "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;

	if not found  then	
		insert into "ContactCashID" values (yy,brnid,0,0,0);
		temtranid :=0;
	end if;

	update "ContactCashID" set tran_id = temtranid+1 where  "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;

	-- todo: แก้ให้รองรับสินทรัพย์อื่นๆ
	kndtext := 'T';
	-- todo: แก้ให้รองรับสาขาอื่นๆ
	brntext := 'BK01';

	if substring(trim(to_char(yy,'9999')) from 1 for 4)='2011' then -- ถ้าเป็นปี 2011 ให้ใช้แบบเก่าไปก่อน
		temid :='1' || to_char(brnid,'FM9') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-22' || to_char(temtranid+1,'FM009');
	else -- หลังปี 2011 ใช้แบบใหม่ได้
		temid := substring(trim(to_char(yy,'9999')) from 3 for 2) || '-22' || to_char(temtranid+1,'FM0009') || kndtext || '-' || brntext;
	end if;

	RETURN temid;



END;



$$;


ALTER FUNCTION public.generate_tran_id(datein date, brnid integer) OWNER TO dev;

--
-- TOC entry 551 (class 1255 OID 64464)
-- Dependencies: 15 1714
-- Name: generate_tran_id_old(date, integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION generate_tran_id_old(datein date, brnid integer) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE
	yy integer;
	temtranid integer;
	temid text:='';
BEGIN

	yy:=EXTRACT(YEAR FROM datein);

	select into  temtranid "ContactCashID"."tran_id" from "ContactCashID" Where "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;
	if not found  then	
		insert into "ContactCashID" values (yy,brnid,0,0,0);
			temtranid :=0;
	end if;
	update "ContactCashID" set tran_id = temtranid+1 where  "ContactCashID"."c_year"=yy and "ContactCashID"."c_branch"=brnid;
	temid :='1' || to_char(brnid,'FM9') || substring(trim(to_char(yy+543,'9999')) from 4 for 1) || '-22' || to_char(temtranid+1,'FM009');
	RETURN temid;
END;
$$;


ALTER FUNCTION public.generate_tran_id_old(datein date, brnid integer) OWNER TO dev;

--
-- TOC entry 552 (class 1255 OID 64465)
-- Dependencies: 15 1714
-- Name: insert_custyear_in_fr(); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION insert_custyear_in_fr() RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfr "Fr"%ROWTYPE;
	temcustyear  integer;
	sucess boolean;

BEGIN

		for temfr in select * from public."Fr" Where ("CustYear" is null) loop
			select into temcustyear  "Fp"."P_CustByYear" from "Fp" where "IDNO" = temfr."IDNO";
			update "Fr" set "CustYear" = temcustyear 
			where "IDNO" = temfr."IDNO" and "R_DueNo" = temfr."R_DueNo" and "R_Receipt" = temfr."R_Receipt";
			

		end loop;

		RETURN true;


END;$$;


ALTER FUNCTION public.insert_custyear_in_fr() OWNER TO dev;

--
-- TOC entry 553 (class 1255 OID 64466)
-- Dependencies: 15 1714
-- Name: insert_vmemo_in_fvat(); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION insert_vmemo_in_fvat() RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfr "Fr"%ROWTYPE;
	temcustyear  integer;
	sucess boolean;

BEGIN

		for temfr in select * from public."Fr" Where ("R_DueNo" =99) loop

			update "FVat" set "V_memo" = temfr."R_Receipt" 
			where "IDNO" = temfr."IDNO" and "V_DueNo" = temfr."R_DueNo" ;
			

		end loop;

		RETURN true;


END;$$;


ALTER FUNCTION public.insert_vmemo_in_fvat() OWNER TO dev;

--
-- TOC entry 554 (class 1255 OID 64467)
-- Dependencies: 15 1714
-- Name: migrate_fr_money(); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION migrate_fr_money() RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temfr "Fr"%ROWTYPE;
	p_money  double precision;
	sucess boolean;

BEGIN

		for temfr in select * from public."Fr"  loop
			select into p_money  "Fp"."P_MONTH" from "Fp" where "IDNO" = temfr."IDNO";
			if temfr."R_Money" <> p_money then
				update "Fr" set "R_Money" = p_money 
				where "IDNO" = temfr."IDNO" and "R_DueNo" = temfr."R_DueNo" and "R_Receipt" = temfr."R_Receipt";
			end if;

		end loop;

		RETURN true;


END;$$;


ALTER FUNCTION public.migrate_fr_money() OWNER TO dev;

--
-- TOC entry 555 (class 1255 OID 64468)
-- Dependencies: 15 1714
-- Name: money_for_reportvat(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION money_for_reportvat(recno text) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
	idno text;
	typerec integer;
	vat double precision;
	temrecno text;
	money double precision;
	vatvalue double precision;
BEGIN
	select into idno,typerec,vat,temrecno "FVat"."IDNO","FVat"."V_DueNo","FVat"."VatValue","FVat"."V_memo" from "FVat" where "FVat"."V_Receipt"=recno;
	if vat=0 then
		money:=0;
	else
		if typerec >0 and typerec < 99 then
			select into money "VCusPayment"."R_Money" from "VCusPayment" where "VCusPayment"."V_Receipt" = recno ;
			if money is null then
				select into money "Fp"."P_MONTH" from "Fp" where "IDNO"=idno;
			end if;
		else
			select into money "Fr"."R_Money" from "Fr" where "R_Receipt"=temrecno;
		end if;		
	end if;

	return money;
END;
$$;


ALTER FUNCTION public.money_for_reportvat(recno text) OWNER TO dev;

--
-- TOC entry 556 (class 1255 OID 64469)
-- Dependencies: 1714 15
-- Name: money_for_reportvat(text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION money_for_reportvat(recno text, idno text) RETURNS double precision
    LANGUAGE plpgsql
    AS $$DECLARE
	
	money double precision;

BEGIN

--	recno = r_receipt of "Fr" 

	if recno is null or recno = '' then
		select into money "Fp"."P_MONTH" from "Fp" where "IDNO"=idno;
	else
		select into money "Fr"."R_Money" from "Fr" where "R_Receipt"=recno;
	end if;
	

--	select into idno,typerec,vat,temrecno "FVat"."IDNO","FVat"."V_DueNo","FVat"."VatValue","FVat"."V_memo" from "FVat" where "FVat"."V_Receipt"=recno;
	
--	if vat=0 then
--		money:=0;
--	else
--		if typerec >0 and typerec < 99 then
--			select into money "VCusPayment"."R_Money" from "VCusPayment" where "VCusPayment"."V_Receipt" = recno ;
--			if money is null then
--				select into money "Fp"."P_MONTH" from "Fp" where "IDNO"=idno;
--			end if;
--		else
--			select into money "Fr"."R_Money" from "Fr" where "R_Receipt"=temrecno;
--		end if;		
--	end if;


--	select into temrecno  v."R_Receipt" from "VRptVat" v where v."V_Receipt"=recno;
--	select into money "Fr"."R_Money" from "Fr" where "R_Receipt"=temrecno;


	return money;
END;
$$;


ALTER FUNCTION public.money_for_reportvat(recno text, idno text) OWNER TO dev;

--
-- TOC entry 557 (class 1255 OID 64470)
-- Dependencies: 15 1714
-- Name: need_rec_cancel_stopvat(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION need_rec_cancel_stopvat(idno text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temaccpayment "VAccPayment"%ROWTYPE;
	countrec integer;
	nonotsent integer;
	temdate text:='';

	dd integer;
	mm integer;
	yy integer;

BEGIN
	dd:=EXTRACT(DAY FROM current_date);
	mm:=EXTRACT(MONTH FROM current_date);
	yy:=EXTRACT(YEAR FROM current_date);
	
	nonotsent:=0;
	select count(*) into countrec from "VAccPayment" where "IDNO"=idno;
	if countrec > 0 then
		temdate := to_char(yy,'9999') || '-' || to_char(mm,'99') || '-01' ;
		select count(*)  into nonotsent from "VAccPayment" where "IDNO"=idno and "DueDate"<to_date(temdate,'YYYY-MM-DD') and "V_Date" is null;
	end if;
	
	update "Fp" set "Fp"."P_StopVat" = false, "Fp"."P_StopVatDate" = null  where "Fp"."IDNO"=idno;
	
	if nonotsent <= 1 then
	     return false;

	else if nonotsent=2 then
	
		     if dd >=14 
		     then  return true;	        -- today is more than 14 vat is already sent  you must print recipt in today
		     else  return false;  	-- check today is less than 14 vat is not yet sent
		     end if;
		     
	     else --- number payment which not sent vat is > 2 
		return true;
	     end if;	
	
	end if;
	
END;
$$;


ALTER FUNCTION public.need_rec_cancel_stopvat(idno text) OWNER TO dev;

--
-- TOC entry 558 (class 1255 OID 64471)
-- Dependencies: 1714 15
-- Name: nw_conversiondatetothaitext(date); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION nw_conversiondatetothaitext(datein date) RETURNS text
    LANGUAGE plpgsql
    AS $$DECLARE

dd integer;
mm integer;
yy integer;
thaitextmonth text;
msg text:='';


BEGIN

	-- ประกาศตัวแปรต่างๆที่จะใช้
	dd:=EXTRACT(DAY FROM datein);
	mm:=EXTRACT(MONTH FROM datein);
	yy:=EXTRACT(YEAR FROM datein);

	if mm=1 then
        thaitextmonth := 'มกราคม';
    
	elsif mm=2 then
        thaitextmonth := 'กุมภาพันธ์';

    elsif mm=3 then
        thaitextmonth := 'มีนาคม';

    elsif mm=4 then
        thaitextmonth := 'เมษายน';

    elsif mm=5 then
        thaitextmonth := 'พฤษภาคม';

    elsif mm=6 then
        thaitextmonth := 'มิถุนายน';

    elsif mm=7 then
        thaitextmonth := 'กรกฎาคม';

    elsif mm=8 then
        thaitextmonth := 'สิงหาคม';

    elsif mm=9 then
        thaitextmonth := 'กันยายน';

    elsif mm=10 then
        thaitextmonth := 'ตุลาคม';

    elsif mm=11 then
        thaitextmonth := 'พฤศจิกายน';

    elsif mm=12 then
        thaitextmonth := 'ธันวาคม';

    end if;


	msg := to_char(dd, '99') || thaitextmonth || to_char(yy+543,'9999');

return  msg;

END;


$$;


ALTER FUNCTION public.nw_conversiondatetothaitext(datein date) OWNER TO dev;

--
-- TOC entry 559 (class 1255 OID 64472)
-- Dependencies: 1714 15
-- Name: pass_cheque(text, text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION pass_cheque(postid text, chqid text, userpass text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	countrec integer;
	temcheque "DetailCheque"%ROWTYPE;
	temidno text;
	temamtpay double precision;
	temrecno text;
	temvatno text;
	timetopay integer;
	chequedate date;
	pmonth double precision;
	pvat double precision;
	firstdate date;
	ptotal integer;
	duedate date;
	dueno integer;
	usevat boolean;
	typerec varchar(1);
	vaccno varchar(20);
	paidstate boolean;
	paybefore boolean;
	accforpayment boolean;
	anyreference text;
	custyear integer;
BEGIN
	select into chequedate,vaccno "FCheque"."DateEnterBank","FCheque"."AccBankEnter" from "FCheque" 
	where "FCheque"."ChequeNo"=chqid and "FCheque"."PostID"=postid and "FCheque"."IsPass" = false;
	if found then
		update "FCheque" set "IsPass"=true , "PassByUser"=userpass 
		where "FCheque"."ChequeNo"=chqid and "FCheque"."PostID"=postid;
		
		select into accforpayment bankofcompany."payment" from bankofcompany where bankofcompany."accno"=vaccno;
		
		for temcheque in select * from "DetailCheque" where "DetailCheque"."ChequeNo"=chqid and "DetailCheque"."PostID"=postid loop
		    if temcheque."IDNO" is null then
			update "DetailCheque" set "IDNO"='ลูกค้านอก' where "DetailCheque"."PostID"=postid;
		    else
			temidno := temcheque."IDNO";
			temamtpay := temcheque."CusAmount";
			if temcheque."TypePay"=1 then
				temrecno := gen_rec_no(chequedate);	
				update "DetailCheque" set "ReceiptNo" = temrecno , "PrnDate" = current_date
				where "DetailCheque"."PostID"=postid and "DetailCheque"."ChequeNo"=chqid and "DetailCheque"."TypePay"=1 and "DetailCheque"."IDNO"=temidno;
						
				select into pmonth,pvat,firstdate,custyear,ptotal  "Fp"."P_MONTH","Fp"."P_VAT","Fp"."P_FDATE","P_CustByYear","Fp"."P_TOTAL"
				from "Fp" where "Fp"."IDNO"= temidno;
				timetopay := temamtpay/(pmonth+pvat);
				paybefore := false;
				select count(*) into dueno from "Fr" where "Fr"."IDNO" = temidno and "Fr"."Cancel"=false and "Fr"."R_DueNo"<99 and "Fr"."R_DueNo">0;
				for j in 1..timetopay loop
					dueno:=dueno+1;
					
					if accforpayment = true then  
						insert into "Fr" values (temidno,dueno,temrecno,chequedate,pmonth,'CU',current_date,'OC',false,null,null,custyear);
					else
						insert into "Fr" values (temidno,dueno,temrecno,chequedate,pmonth,'CCA',current_date,'OC',false,null,null,custyear);
					end if;
					
					if dueno = ptotal then
						update "Fp" set "P_ACCLOSE"=true , "P_CLDATE"=chequedate  where "Fp"."IDNO"=temidno;
					end if;

					----------------check vat print out already
					duedate := firstdate;
					select into  paidstate "FVat"."Paid_Status"  from "FVat" where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
					if found then
						update "FVat" set "Paid_Status" = true  where "FVat"."IDNO"=temidno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
					else
						for i in 1..dueno-1 loop
							duedate := firstdate+ (i||'month')::interval;
						end loop;
		
						if duedate <= chequedate  then
							temvatno := gen_vat_no(duedate);
						else
							if paybefore = false then 
								temvatno := gen_vat_no(chequedate);
								paybefore:= true;
							end if;
							duedate := chequedate;
						end if;
	
						insert into "FVat" values (temidno,dueno,temvatno,duedate,pvat,current_date,true,false,null);
					end if;
					-------------------- end check vat ------------------
				end loop;
			else
				select into usevat,typerec  "TypePay"."UseVat","TypePay"."TypeRec" from "TypePay" where "TypePay"."TypeID"=temcheque."TypePay";
				if usevat=true then
					select into custyear "Fp"."P_CustByYear" from "Fp" where "Fp"."IDNO"= temidno;
					temrecno := gen_rec_no(chequedate);
					update "DetailCheque" set "ReceiptNo" = temrecno , "PrnDate" = current_date
					where "DetailCheque"."PostID"=postid and "DetailCheque"."ChequeNo"=chqid and "DetailCheque"."TypePay"=temcheque."TypePay" and "DetailCheque"."IDNO"=temidno;
					anyreference := post_any_reference(temcheque."TypePay",temidno,temcheque."CusAmount");					
					if accforpayment = true then  
						insert into "Fr" values (temidno,temcheque."TypePay",temrecno,chequedate,amt_before_vat(temcheque."CusAmount"),'CU',current_date,'OC',false,null,anyreference,null);
					else 
						insert into "Fr" values (temidno,temcheque."TypePay",temrecno,chequedate,amt_before_vat(temcheque."CusAmount"),'CCA',current_date,'OC',false,null,anyreference,null);
					end if;
					pvat := temcheque."CusAmount"-amt_before_vat(temcheque."CusAmount");
					temvatno := gen_vat_no(chequedate);
					insert into "FVat" values (temidno,temcheque."TypePay",temvatno,chequedate,pvat,current_date,true,false,temrecno);

				else
					if typerec = 'R' then
						temrecno := gen_rec_no(chequedate);
						if accforpayment = true then  
						    insert into "Fr" values (temidno,temcheque."TypePay",temrecno,chequedate,temcheque."CusAmount",'CU',current_date,'OC',false,null,null,custyear);
						else
						    insert into "Fr" values (temidno,temcheque."TypePay",temrecno,chequedate,temcheque."CusAmount",'CCA',current_date,'OC',false,null,null,custyear);
						end if;
					elsif typerec='K' then
						temrecno := gen_k_no(chequedate);
						if accforpayment = true then  
						    insert into "FOtherpay" values (temidno,chequedate,temrecno,temamtpay,temcheque."TypePay",'CU',current_date,'OC',false,null,null);
						else
						    insert into "FOtherpay" values (temidno,chequedate,temrecno,temamtpay,temcheque."TypePay",'CCA',current_date,'OC',false,null,null);
						end if;
					elsif typerec='N' then
						temrecno := gen_n_no(chequedate);
						anyreference := post_any_reference(temcheque."TypePay",temidno,temamtpay);
						if accforpayment = true then  
						    insert into "FOtherpay" values (temidno,chequedate,temrecno,temamtpay,temcheque."TypePay",'CU',current_date,'OC',false,null,anyreference);
						else
						    insert into "FOtherpay" values (temidno,chequedate,temrecno,temamtpay,temcheque."TypePay",'CCA',current_date,'OC',false,null,anyreference);
						end if;
					end if;

					update "DetailCheque" set "ReceiptNo" = temrecno , "PrnDate" = current_date
					where "DetailCheque"."PostID"=postid and "DetailCheque"."ChequeNo"=chqid and "DetailCheque"."TypePay"=temcheque."TypePay" and "DetailCheque"."IDNO"=temidno;
					
				end if;
			end if;
		    end if;
		end loop;
	end if;
	RETURN true;
END;
$$;


ALTER FUNCTION public.pass_cheque(postid text, chqid text, userpass text) OWNER TO dev;

--
-- TOC entry 560 (class 1255 OID 64473)
-- Dependencies: 1714 15
-- Name: pass_tranpay(text, text, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION pass_tranpay(postid text, idno text, userpass text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	countrec integer;
	temdtpay "DetailTranpay"%ROWTYPE;
	temrecno text;
	temvatno text;
	trdate date;
	pmonth double precision;
	pvat double precision;
	firstdate date;
	ptotal integer;
	duedate date;
	timetopay integer;
	dueno integer;
	bankno varchar(4);
	paidstate boolean;
	paybefore boolean:=false;
	usevat boolean;
	typerec varchar(1);
	comefrom text;
	terminalid text;
	anyreference text;
	custyear integer;

BEGIN
	select into trdate , bankno ,terminalid "TranPay"."tr_date","TranPay"."bank_no","TranPay"."terminal_id" from "TranPay" where "TranPay"."PostID"=postid;
	if found then
		update "TranPay" set "post_on_asa_sys"=true , "post_by"=userpass 
		where "TranPay"."PostID"=postid;
		
		if terminalid = 'TR-ACC' then
			comefrom := 'TR-ACC';
		else 
			comefrom := 'Bill Payment';
		end if;		
		
		for temdtpay in select * from "DetailTranpay" where  "DetailTranpay"."PostID"=postid  and "DetailTranpay"."Cancel"=false loop
			if temdtpay."TypePay"=1 then
				temrecno := gen_rec_no(trdate);
				update "DetailTranpay" set "ReceiptNo" = temrecno , "PrnDate" = current_date
				where "DetailTranpay"."PostID"=postid and  "DetailTranpay"."TypePay"=1 and "DetailTranpay"."IDNO"=idno;
						
				select into pmonth,pvat,firstdate,custyear,ptotal  "Fp"."P_MONTH","Fp"."P_VAT","Fp"."P_FDATE","P_CustByYear","Fp"."P_TOTAL"
				from "Fp" where "Fp"."IDNO"= idno;
				timetopay := temdtpay."Amount"/(pmonth+pvat);
				
				select count(*) into dueno from "Fr" where "Fr"."IDNO" = idno and "Fr"."Cancel"=false and "Fr"."R_DueNo"<99 and "Fr"."R_DueNo">0;
				for j in 1..timetopay loop
					dueno:=dueno+1;

					insert into "Fr" values (idno,dueno,temrecno,trdate,pmonth,'CA',current_date,bank_code(bankno),false,comefrom,null,custyear);

					if dueno = ptotal then
						update "Fp" set "P_ACCLOSE"=true , "P_CLDATE"=trdate  where "Fp"."IDNO"=idno;
					end if;
					
					----------------check vat print out already
					duedate := firstdate;
					select into  paidstate "FVat"."Paid_Status"  from "FVat" where "FVat"."IDNO"=idno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
					if found then
						update "FVat" set "Paid_Status" = true  where "FVat"."IDNO"=idno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
					else
						for i in 1..dueno-1 loop
							duedate := firstdate+ (i||'month')::interval;
						end loop;
		
						if duedate <= trdate  then
							temvatno := gen_vat_no(duedate);
						else
							if paybefore = false then 
								temvatno := gen_vat_no(trdate);
								paybefore:= true;
							end if;
							duedate := trdate;
						end if;
	
						insert into "FVat" values (idno,dueno,temvatno,duedate,pvat,current_date,true,false,null);
					end if;
					-------------------- end check vat ------------------
					
				end loop;

			else  --- temdtpay."TypePay"<> 1
				select into usevat,typerec  "TypePay"."UseVat","TypePay"."TypeRec" from "TypePay" where "TypePay"."TypeID"=temdtpay."TypePay";
				if usevat=true then
					select into custyear "Fp"."P_CustByYear" from "Fp" where "Fp"."IDNO"= idno;
					temrecno := gen_rec_no(trdate);
					update "DetailTranpay" set "ReceiptNo" = temrecno , "PrnDate" = current_date
					where "DetailTranpay"."PostID"=postid  and "DetailTranpay"."TypePay"=temdtpay."TypePay" and "DetailTranpay"."IDNO"=idno;
					anyreference := post_any_reference(temdtpay."TypePay",idno,temdtpay."Amount");					
					insert into "Fr" values (idno,temdtpay."TypePay",temrecno,trdate,amt_before_vat(temdtpay."Amount"),'CA',current_date,bank_code(bankno),false,comefrom,anyreference,null);

					pvat := temdtpay."Amount"-amt_before_vat(temdtpay."Amount");
					temvatno := gen_vat_no(trdate);
					insert into "FVat" values (idno,temdtpay."TypePay",temvatno,trdate,pvat,current_date,true,false,temrecno);

				else
					if typerec = 'R' then
						temrecno := gen_rec_no(trdate);
						insert into "Fr" values (idno,temdtpay."TypePay",temrecno,trdate,temdtpay."Amount",'CA',current_date,bank_code(bankno),false,comefrom,null,custyear);
					elsif typerec='K' then
						temrecno := gen_k_no(trdate);
						insert into "FOtherpay" values (idno,trdate,temrecno,temdtpay."Amount",temdtpay."TypePay",'CA',current_date,bank_code(bankno),false,comefrom,null);
					elsif typerec='N' then
						temrecno := gen_n_no(trdate);
						if temdtpay."TypePay" = 133  and temdtpay."RefID" <> '' then
							insert into "FOtherpay" values (idno,trdate,temrecno,temdtpay."Amount",temdtpay."TypePay",'CA',current_date,bank_code(bankno),false,comefrom,null);
							--- From Original: temrecno:=gen_k_no(recdate);
							--- TA-NV Edited: temrecno:=gen_k_no(trdate);
							temrecno:=gen_k_no(trdate);				
							insert into "FOtherpay" values (temdtpay."RefID",trdate,temrecno,temdtpay."Amount",200,'CA',current_date,'DEP',false,null,idno);						
						else
							anyreference := post_any_reference(temdtpay."TypePay",idno,temdtpay."Amount");
							insert into "FOtherpay" values (idno,trdate,temrecno,temdtpay."Amount",temdtpay."TypePay",'CA',current_date,bank_code(bankno),false,comefrom,anyreference);
						end if;
					end if;

					update "DetailTranpay" set "ReceiptNo" = temrecno , "PrnDate" = current_date
					where "DetailTranpay"."PostID"=postid and "DetailTranpay"."TypePay"=temdtpay."TypePay" and "DetailTranpay"."IDNO"=idno;
				end if;	
				
			end if;
		end loop;
	end if;
	RETURN true;
END;
$$;


ALTER FUNCTION public.pass_tranpay(postid text, idno text, userpass text) OWNER TO dev;

--
-- TOC entry 561 (class 1255 OID 64474)
-- Dependencies: 15 1714
-- Name: pmt(double precision, integer, double precision); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION pmt(rate double precision, nper integer, pv double precision) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
BEGIN
  IF rate > 0 THEN
    RETURN (rate*(pv*power((1+ rate),nper)))/(1-power((1+ rate),nper));
  ELSE
    RETURN -1 * (pv / nper);
  END IF;
END;
$$;


ALTER FUNCTION public.pmt(rate double precision, nper integer, pv double precision) OWNER TO dev;

--
-- TOC entry 562 (class 1255 OID 64475)
-- Dependencies: 15 1714
-- Name: post_any_reference(integer, text, double precision); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION post_any_reference(typepay integer, vidno text, amt double precision) RETURNS text
    LANGUAGE plpgsql
    AS $$

DECLARE
	anyref text;
	assettype integer;

BEGIN

	if typepay = 102 then  
		select into anyref insure."InsureUnforce"."InsUFIDNO" from insure."InsureUnforce" 
		where insure."InsureUnforce"."IDNO"=vidno and insure."InsureUnforce"."CusPayReady"=false and insure."InsureUnforce"."Cancel"=false
		and insure.outstanding_insureunforce(insure."InsureUnforce"."InsUFIDNO") > '0'
		order by insure."InsureUnforce"."InsUFIDNO" ASC;
		
		if anyref notnull then
			if round(amt*100)/100 >= insure.outstanding_insureunforce(anyref) then
				update insure."InsureUnforce" set "CusPayReady"=true where insure."InsureUnforce"."InsUFIDNO"=anyref;
			end if;
			return anyref;
		else
			return null;
		end if;
		
	elsif typepay=103 then
		select into anyref insure."InsureForce"."InsFIDNO" from insure."InsureForce" 
		where insure."InsureForce"."IDNO"=vidno and insure."InsureForce"."CusPayReady"=false and insure."InsureForce"."Cancel"=false
		and insure.outstanding_insforce(insure."InsureForce"."InsFIDNO") > '0'
		order by insure."InsureForce"."InsFIDNO" ASC;

		if anyref notnull then
			if round(amt*100)/100 >= insure.outstanding_insforce(anyref) then
				update insure."InsureForce" set "CusPayReady"=true where insure."InsureForce"."InsFIDNO"=anyref;
			end if;
			return anyref;
		else
			return null;
		end if;
		
	elsif typepay=0 then
		select into assettype "VContact"."asset_type" from "VContact" where "VContact"."IDNO"=vidno;
		if assettype=2 then
			select into anyref gas."PoGas"."poid" from gas."PoGas" 
			where gas."PoGas"."idno"=vidno and gas."PoGas"."status_po"=true;
			return anyref;
		else
			return null;
		end if;

	elsif typepay=99 then
		select into assettype "VContact"."asset_type" from "VContact" where "VContact"."IDNO"=vidno;
		if assettype=2 then
			select into anyref gas."PoGas"."poid" from gas."PoGas" 
			where gas."PoGas"."idno"=vidno and gas."PoGas"."status_po"=true;
			return anyref;
		else
			return null;
		end if;

	elsif typepay=110 then
		select into anyref gas."PoGas"."poid" from gas."PoGas" 
		where gas."PoGas"."idno"=vidno and gas."PoGas"."status_po"=true;
		return anyref;

	elsif typepay=101 then
		select into anyref carregis."CarTaxDue"."IDCarTax" from carregis."CarTaxDue" 
		where carregis."CarTaxDue"."IDNO"=vidno and carregis."CarTaxDue"."cuspaid"=false and carregis."CarTaxDue"."TypeDep"=typepay
		order by carregis."CarTaxDue"."IDCarTax" ASC;

		if anyref notnull then
			if amt >= carregis.outstanding_registype(anyref) then
				update carregis."CarTaxDue" set "cuspaid"=true where carregis."CarTaxDue"."IDCarTax"=anyref;
			end if;
			return anyref;
		else
			return null;
		end if;

	elsif typepay=105 then
		select into anyref carregis."CarTaxDue"."IDCarTax" from carregis."CarTaxDue" 
		where carregis."CarTaxDue"."IDNO"=vidno and carregis."CarTaxDue"."cuspaid"=false and carregis."CarTaxDue"."TypeDep"=typepay
		order by carregis."CarTaxDue"."IDCarTax" ASC;
		if anyref notnull then
			if amt >= carregis.outstanding_registype(anyref) then
				update carregis."CarTaxDue" set "cuspaid"=true where carregis."CarTaxDue"."IDCarTax"=anyref;
			end if;
			return anyref;
		else
			return null;
		end if;	

	elsif typepay=106 then
		select into anyref carregis."CarTaxDue"."IDCarTax" from carregis."CarTaxDue" 
		where carregis."CarTaxDue"."IDNO"=vidno and carregis."CarTaxDue"."cuspaid"=false and carregis."CarTaxDue"."TypeDep"=typepay
		order by carregis."CarTaxDue"."IDCarTax" ASC;
		if anyref notnull then
			if amt >= carregis.outstanding_registype(anyref) then
				update carregis."CarTaxDue" set "cuspaid"=true where carregis."CarTaxDue"."IDCarTax"=anyref;
			end if;
			return anyref;
		else
			return null;
		end if;

	elsif typepay=107 then
		select into anyref carregis."CarTaxDue"."IDCarTax" from carregis."CarTaxDue" 
		where carregis."CarTaxDue"."IDNO"=vidno and carregis."CarTaxDue"."cuspaid"=false and carregis."CarTaxDue"."TypeDep"=typepay
		order by carregis."CarTaxDue"."IDCarTax" ASC;
		if anyref notnull then
			if amt >= carregis.outstanding_registype(anyref) then
				update carregis."CarTaxDue" set "cuspaid"=true where carregis."CarTaxDue"."IDCarTax"=anyref;
			end if;
			return anyref;
		else
			return null;
		end if;
		
	elsif typepay=111 then
		select into anyref carregis."CarTaxDue"."IDCarTax" from carregis."CarTaxDue" 
		where carregis."CarTaxDue"."IDNO"=vidno and carregis."CarTaxDue"."cuspaid"=false and carregis."CarTaxDue"."TypeDep"=typepay
		order by carregis."CarTaxDue"."IDCarTax" ASC;
		if anyref notnull then
			if amt >= carregis.outstanding_registype(anyref) then
				update carregis."CarTaxDue" set "cuspaid"=true where carregis."CarTaxDue"."IDCarTax"=anyref;
			end if;
			return anyref;
		else
			return null;
		end if;
		
	elsif typepay=112 then
		select into anyref carregis."CarTaxDue"."IDCarTax" from carregis."CarTaxDue" 
		where carregis."CarTaxDue"."IDNO"=vidno and carregis."CarTaxDue"."cuspaid"=false and carregis."CarTaxDue"."TypeDep"=typepay
		order by carregis."CarTaxDue"."IDCarTax" ASC;
		if anyref notnull then
			if amt >= carregis.outstanding_registype(anyref) then
				update carregis."CarTaxDue" set "cuspaid"=true where carregis."CarTaxDue"."IDCarTax"=anyref;
			end if;
			return anyref;
		else
			return null;
		end if;
	else
		return null;
	end if;

END;


$$;


ALTER FUNCTION public.post_any_reference(typepay integer, vidno text, amt double precision) OWNER TO dev;

--
-- TOC entry 563 (class 1255 OID 64476)
-- Dependencies: 15 1714
-- Name: print_any_receipt(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION print_any_receipt(recno text, OUT idno text, OUT money double precision, OUT vat double precision, OUT paydetail text, OUT pd2 text, OUT payby text, OUT discount double precision) RETURNS record
    LANGUAGE plpgsql
    AS $$DECLARE
	temrecno text;
	countrec integer;
	typerec integer;
	totalpay integer;
	firstdue integer;
	lastdue integer;
	duedate date;
	firstdate date;
	lastdate date;
	typebank varchar(3);
	tempostid text;
	temchqno text;
	bankname text;
	bankbranch text;
BEGIN
	if substring(recno from 3 for 1) = 'R' then
		select count(*) into countrec from "Fr" where "R_Receipt" = recno;
		select into idno,typerec,money,typebank "Fr"."IDNO","Fr"."R_DueNo","Fr"."R_Money","Fr"."R_Bank" from "Fr" where "Fr"."R_Receipt"=recno;
		if countrec = 1 then 
			if typerec >0 and typerec < 99 then
				select count(*) into totalpay from "VCusPayment" where "IDNO"=idno;
				
				if typerec=totalpay then
					select into discount "Fp"."P_SL" from "Fp" where "IDNO"=idno;
				else 
					discount:=0;
				end if;
				
				select into vat,duedate "VCusPayment"."VatValue","VCusPayment"."DueDate" from "VCusPayment" where "VCusPayment"."R_Receipt" = recno ;
				paydetail := 'ค่างวดที่ '|| to_char(typerec,'99')||'/'||to_char(totalpay,'99');
				pd2 := to_char(duedate,'DD/MM/YYYY');
				if typebank = 'CU' then
					select into tempostid,temchqno "DetailCheque"."PostID","DetailCheque"."ChequeNo" from "DetailCheque" where "ReceiptNo"=recno;
					select into bankname,bankbranch "FCheque"."BankName","FCheque"."BankBranch" from "FCheque" where "PostID"=tempostid and "ChequeNo"=temchqno;
					payby := 'ชำระโดย : เช็คเลขที่ '||temchqno ||' ธนาคาร '|| bankname ||' สาขา '||bankbranch;
				else
					payby:='ชำระโดย : เงินสด';
				end if;
			else
				select into vat "FVat"."VatValue" from "FVat" where "V_memo"=recno;
				select into paydetail "TypePay"."TName" from "TypePay" where "TypeID"=typerec;
			end if;		
		else  -- pay payment more than 1 time 
			select count(*) into totalpay from "VCusPayment" where "IDNO"=idno;
			select into firstdue ,firstdate "VCusPayment"."DueNo","VCusPayment"."DueDate" from "VCusPayment" where "R_Receipt"=recno;
			for lastdue,lastdate in select "VCusPayment"."DueNo","VCusPayment"."DueDate" from "VCusPayment" where "R_Receipt"=recno loop
			end loop;

			if lastdue=totalpay then
				select into discount "Fp"."P_SL" from "Fp" where "IDNO"=idno;
			else 
				discount:=0;
			end if;
							
			select into  money ,vat  sum("VCusPayment"."R_Money"), sum("VCusPayment"."VatValue") from "VCusPayment" where "VCusPayment"."R_Receipt" = recno ;			
			paydetail := 'ค่างวดที่ '|| to_char(firstdue,'99')||'-'||to_char(lastdue,'99')||'/'||to_char(totalpay,'99');
			pd2:=to_char(firstdate,'DD/MM/YYYY')||'-'||to_char(lastdate,'DD/MM/YYYY');
			select into typebank "Fr"."R_Bank" from "Fr" where "Fr"."R_Receipt"=recno;
			if typebank = 'CU' then
				select into tempostid,temchqno "DetailCheque"."PostID","DetailCheque"."ChequeNo" from "DetailCheque" where "ReceiptNo"=recno;
				select into bankname,bankbranch "FCheque"."BankName","FCheque"."BankBranch" from "FCheque" where "PostID"=tempostid and "ChequeNo"=temchqno;
				payby := 'ชำระโดย : เช็ค ' || bankname ||bankbranch||' เลขที่ '||temchqno;
			else
				payby:='ชำระโดย : เงินสด';
			end if;
		end if;
		
	elsif substring(recno from 3 for 1) = 'N' then
		select into idno,typerec,money "FOtherpay"."IDNO","FOtherpay"."O_Type","FOtherpay"."O_MONEY" from "FOtherpay" where "FOtherpay"."O_RECEIPT"=recno;
		vat := 0;
		select into paydetail "TypePay"."TName" from "TypePay" where "TypeID"=typerec;
		pd2:='';
		payby:='ชำระโดย : เงินสด';
	
	elsif substring(recno from 3 for 1) = 'V' then
		select count(*) into countrec from "FVat" where "V_Receipt" = recno;
		select into idno,typerec,vat,temrecno "FVat"."IDNO","FVat"."V_DueNo","FVat"."VatValue","FVat"."V_memo" from "FVat" where "FVat"."V_Receipt"=recno;
		if countrec = 1 then 
			if typerec >0 and typerec < 99 then
				select count(*) into totalpay from "VCusPayment" where "IDNO"=idno;
				select into money,duedate "VCusPayment"."R_Money","VCusPayment"."DueDate" from "VCusPayment" where "VCusPayment"."V_Receipt" = recno ;
				if money is null then
					select into money "Fp"."P_MONTH" from "Fp" where "IDNO"=idno;
				end if;
				paydetail := 'ค่างวดที่ '|| to_char(typerec,'99')||'/'||to_char(totalpay,'99');
				pd2:= to_char(duedate,'DD/MM/YYYY');
			else
				select into money "Fr"."R_Money" from "Fr" where "R_Receipt"=temrecno;
				select into paydetail "TypePay"."TName" from "TypePay" where "TypeID"=typerec;
				pd2:='';
			end if;		
		else  -- pay payment more than 1 time 
			select count(*) into totalpay from "VCusPayment" where "IDNO"=idno;
			select into firstdue,firstdate "VCusPayment"."DueNo","VCusPayment"."DueDate" from "VCusPayment" where "V_Receipt"=recno;
			for lastdue,lastdate in select "VCusPayment"."DueNo","VCusPayment"."DueDate" from "VCusPayment" where "V_Receipt"=recno loop
			end loop;
			select into  money ,vat  sum("VCusPayment"."R_Money"), sum("VCusPayment"."VatValue") from "VCusPayment" where "VCusPayment"."V_Receipt" = recno ;			
			paydetail := 'ค่างวดที่ '|| to_char(firstdue,'99')||'-'||to_char(lastdue,'99')||'/'||to_char(totalpay,'99');
			pd2:=to_char(firstdate,'DD/MM/YYYY')||'-'||to_char(lastdate,'DD/MM/YYYY');
		end if;
		payby:='';
	elsif substring(recno from 3 for 1) = 'K' then
		select into idno,typerec,money "FOtherpay"."IDNO","FOtherpay"."O_Type","FOtherpay"."O_MONEY" from "FOtherpay" where "FOtherpay"."O_RECEIPT"=recno;
		vat := 0;
		select into paydetail "TypePay"."TName" from "TypePay" where "TypeID"=typerec;
		pd2:='';
		payby:='ชำระโดย : เงินสด';
	
	end if;

END;
$$;


ALTER FUNCTION public.print_any_receipt(recno text, OUT idno text, OUT money double precision, OUT vat double precision, OUT paydetail text, OUT pd2 text, OUT payby text, OUT discount double precision) OWNER TO dev;

--
-- TOC entry 564 (class 1255 OID 64477)
-- Dependencies: 1714 15
-- Name: rate(integer, double precision, double precision); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION rate(nper integer, pmtrate double precision, pv double precision) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
  diff      double precision := 0.000001;
  r         double precision;
  expo      integer;
  direction boolean;
  p         double precision;
  adj       double precision;
  done      boolean;
BEGIN
  IF nper * pmtrate = pv THEN
    r := 0;
  ELSE
    r := 0.1;
    direction := true;
    expo := 0;
    p := 0;
    done = false;
    
    WHILE NOT done LOOP
    
      IF direction = true THEN
    
        IF p > pmtrate THEN
            direction := false;
            expo := expo - 1;
        ELSE 
            adj := power(10 , expo);
            r := r + adj;
        END IF;
    
      ELSE 
    
        IF p < pmtrate THEN
            direction := true;
            expo := expo - 1;
        ELSE 
            adj := power(10 , expo);
            r := r - adj;
        END IF;    
      
      END IF;
      
      p := abs(pmt(r , nper , pv));         
         
      IF abs(p - pmtrate) < diff THEN
        done := true;
      END IF;     
    
    END LOOP; 
    
  END IF;
  
  RETURN r;
END;
$$;


ALTER FUNCTION public.rate(nper integer, pmtrate double precision, pv double precision) OWNER TO dev;

--
-- TOC entry 565 (class 1255 OID 64478)
-- Dependencies: 15 1714
-- Name: receipt_detail(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION receipt_detail(recno text, OUT idno text, OUT rec_date date, OUT cus_name text, OUT asset_type text, OUT asset_id text, OUT money double precision, OUT vat double precision, OUT paydetail text, OUT pd2 text, OUT discount double precision, OUT typebank character varying, OUT memo text) RETURNS record
    LANGUAGE plpgsql
    AS $$DECLARE
	temrecno text;
	cusid text;
	as_type integer;
	as_id text;
	countrec integer;
	typerec integer;
	totalpay integer;
	firstdue integer;
	lastdue integer;
	duedate date;
	firstdate date;
	lastdate date;
	tempostid text;
	temchqno text;
	bankname text;
	bankbranch text;
BEGIN
-----------------------------
-- Input receipt nuber
-- Output 
-- 	- idno  
--	- rec_date : date on receipt
--	- cus_name : customer name
--	- asset_type : as toyota or bigas
--	- asset_id : regis of car , id of body gas
--	- money : value money in receipt
--	- vat : value of vat in receipt ( only type 'R')
--	- paydetail : type of pay
--	- pd2 : due date of payment (only payment time)
--	- discount : discount in receipt 
--	- typebank : pay by cash over counter or any bank sa SCB,TMB,KTB
--	- memo : chanel to pay as bill payment , transfer 
-----------------------------

	if substring(recno from 3 for 1) = 'R' then
		select count(*) into countrec from "Fr" where "R_Receipt" = recno;
		select into idno,rec_date,typerec,money,typebank,memo "Fr"."IDNO","Fr"."R_Date","Fr"."R_DueNo","Fr"."R_Money","Fr"."PayType" ,"Fr"."R_memo" from "Fr" where "Fr"."R_Receipt"=recno;
		select into cusid,as_type,as_id,discount "Fp"."CusID","Fp"."asset_type","Fp"."asset_id","Fp"."P_SL" from "Fp" where "IDNO"=idno;
		cus_name:= customer_name(cusid);
		asset_type := asset_name(as_type,as_id);
		asset_id := asset_regis(as_type,as_id);
		
		if countrec = 1 then 
			if typerec >0 and typerec < 99 then
				select count(*) into totalpay from "VCusPayment" where "IDNO"=idno;
				
				if typerec=totalpay then
					select into discount "Fp"."P_SL" from "Fp" where "IDNO"=idno;
				else 
					discount:=0;
				end if;
				
				select into vat,duedate "VCusPayment"."VatValue","VCusPayment"."DueDate" from "VCusPayment" where "VCusPayment"."R_Receipt" = recno ;
				paydetail := 'ค่างวดที่ '|| to_char(typerec,'99')||'/'||to_char(totalpay,'99');
				pd2 := to_char(duedate,'DD/MM/YYYY');
				if typebank = 'CU' then
					select into tempostid,temchqno "DetailCheque"."PostID","DetailCheque"."ChequeNo" from "DetailCheque" where "ReceiptNo"=recno;
					select into bankname,bankbranch "FCheque"."BankName","FCheque"."BankBranch" from "FCheque" where "PostID"=tempostid and "ChequeNo"=temchqno;
					memo := 'ชำระโดย : เช็คเลขที่ '||temchqno ||' ธนาคาร '|| bankname ||' สาขา '||bankbranch;
				end if;				
			else
				select into vat "FVat"."VatValue" from "FVat" where "V_memo"=recno;
				select into paydetail "TypePay"."TName" from "TypePay" where "TypeID"=typerec;
			end if;		
		else  -- pay payment more than 1 time 
			select count(*) into totalpay from "VCusPayment" where "IDNO"=idno;
			select into firstdue ,firstdate "VCusPayment"."DueNo","VCusPayment"."DueDate" from "VCusPayment" where "R_Receipt"=recno;
			for lastdue,lastdate in select "VCusPayment"."DueNo","VCusPayment"."DueDate" from "VCusPayment" where "R_Receipt"=recno loop
			end loop;

			if lastdue=totalpay then
				select into discount "Fp"."P_SL" from "Fp" where "IDNO"=idno;
			else 
				discount:=0;
			end if;
							
			select into  money ,vat  sum("VCusPayment"."R_Money"), sum("VCusPayment"."VatValue") from "VCusPayment" where "VCusPayment"."R_Receipt" = recno ;			
			paydetail := 'ค่างวดที่ '|| to_char(firstdue,'99')||'-'||to_char(lastdue,'99')||'/'||to_char(totalpay,'99');
			pd2:=to_char(firstdate,'DD/MM/YYYY')||'-'||to_char(lastdate,'DD/MM/YYYY');
			if typebank = 'CU' then
				select into tempostid,temchqno "DetailCheque"."PostID","DetailCheque"."ChequeNo" from "DetailCheque" where "ReceiptNo"=recno;
				select into bankname,bankbranch "FCheque"."BankName","FCheque"."BankBranch" from "FCheque" where "PostID"=tempostid and "ChequeNo"=temchqno;
				memo := 'ชำระโดย : เช็ค ' || bankname ||bankbranch||' เลขที่ '||temchqno;
			end if;
		end if;
		
	elsif substring(recno from 3 for 1) = 'N' then
		select into idno,rec_date,typerec,money,typebank,memo "FOtherpay"."IDNO","FOtherpay"."O_DATE","FOtherpay"."O_Type","FOtherpay"."O_MONEY","FOtherpay"."PayType","FOtherpay"."O_memo" from "FOtherpay" where "FOtherpay"."O_RECEIPT"=recno;
		select into cusid,as_type,as_id,discount "Fp"."CusID","Fp"."asset_type","Fp"."asset_id","Fp"."P_SL" from "Fp" where "IDNO"=idno;
		cus_name:= customer_name(cusid);
		asset_type := asset_name(as_type,as_id);
		asset_id := asset_regis(as_type,as_id);
		vat := 0;
		select into paydetail "TypePay"."TName" from "TypePay" where "TypeID"=typerec;
		pd2:='';
	
	elsif substring(recno from 3 for 1) = 'V' then
		select count(*) into countrec from "FVat" where "V_Receipt" = recno;
		select into idno,rec_date,typerec,vat,temrecno "FVat"."IDNO","FVat"."V_Date","FVat"."V_DueNo","FVat"."VatValue","FVat"."V_memo" from "FVat" where "FVat"."V_Receipt"=recno;

		select into cusid,as_type,as_id,discount "Fp"."CusID","Fp"."asset_type","Fp"."asset_id","Fp"."P_SL" from "Fp" where "IDNO"=idno;
		cus_name:= customer_name(cusid);
		asset_type := asset_name(as_type,as_id);
		asset_id := asset_regis(as_type,as_id);
		
		if countrec = 1 then 
			if typerec >0 and typerec < 99 then
				select count(*) into totalpay from "VCusPayment" where "IDNO"=idno;
				select into money,duedate "VCusPayment"."R_Money","VCusPayment"."DueDate" from "VCusPayment" where "VCusPayment"."V_Receipt" = recno ;
				if money is null then
					select into money "Fp"."P_MONTH" from "Fp" where "IDNO"=idno;
				end if;
				paydetail := 'ค่างวดที่ '|| to_char(typerec,'99')||'/'||to_char(totalpay,'99');
				pd2:= to_char(duedate,'DD/MM/YYYY');
			else
				select into money "Fr"."R_Money" from "Fr" where "R_Receipt"=temrecno;
				select into paydetail "TypePay"."TName" from "TypePay" where "TypeID"=typerec;
				pd2:='';
			end if;		
		else  -- pay payment more than 1 time 
			select count(*) into totalpay from "VCusPayment" where "IDNO"=idno;
			select into firstdue,firstdate "VCusPayment"."DueNo","VCusPayment"."DueDate" from "VCusPayment" where "V_Receipt"=recno;
			for lastdue,lastdate in select "VCusPayment"."DueNo","VCusPayment"."DueDate" from "VCusPayment" where "V_Receipt"=recno loop
			end loop;
			select into  money ,vat  sum("VCusPayment"."R_Money"), sum("VCusPayment"."VatValue") from "VCusPayment" where "VCusPayment"."V_Receipt" = recno ;			
			paydetail := 'ค่างวดที่ '|| to_char(firstdue,'99')||'-'||to_char(lastdue,'99')||'/'||to_char(totalpay,'99');
			pd2:=to_char(firstdate,'DD/MM/YYYY')||'-'||to_char(lastdate,'DD/MM/YYYY');
		end if;

	elsif substring(recno from 3 for 1) = 'K' then
		select into idno,rec_date,typerec,money "FOtherpay"."IDNO","FOtherpay"."O_DATE","FOtherpay"."O_Type","FOtherpay"."O_MONEY" from "FOtherpay" where "FOtherpay"."O_RECEIPT"=recno;
		select into cusid,as_type,as_id,discount "Fp"."CusID","Fp"."asset_type","Fp"."asset_id","Fp"."P_SL" from "Fp" where "IDNO"=idno;
		cus_name:= customer_name(cusid);
		asset_type := asset_name(as_type,as_id);
		asset_id := asset_regis(as_type,as_id);
		vat := 0;
		select into paydetail "TypePay"."TName" from "TypePay" where "TypeID"=typerec;
		pd2:='';
	end if;

END;
$$;


ALTER FUNCTION public.receipt_detail(recno text, OUT idno text, OUT rec_date date, OUT cus_name text, OUT asset_type text, OUT asset_id text, OUT money double precision, OUT vat double precision, OUT paydetail text, OUT pd2 text, OUT discount double precision, OUT typebank character varying, OUT memo text) OWNER TO dev;

--
-- TOC entry 566 (class 1255 OID 64480)
-- Dependencies: 1714 15
-- Name: select_deposit_remain(text, double precision, date, integer, text, double precision); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION select_deposit_remain(idno text, useamt double precision, usedate date, paytype integer, trantoidno text, discount double precision) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE


-----------------------------------------
--------- input parameter ---------------
-----------------------------------------
-- idno is id of customer payment
-- useamt is amount of money which customerpay
-- paytype is type of pay (see in table "TypePay")
-- trantoidno is id of customer which sent amount to , use paytype=133 only
-- discount is amount of discount when customer close account , default=0  use paytype = 1 only
-----------------------------------------



	outrecno text:='';
	
	pmonth double precision;
	pvat double precision;
	firstdate date;
	ptotal integer;
	timetopay integer;
	dueno integer;
	duedate date;
	paidstate boolean;
	temvatno text;
	paybefore boolean:=false;
	usevat boolean;
	custyear integer;
	
	temdpremain "VDepositRemain"%ROWTYPE;
	remainamt double precision;
	k_recno text;
	recno text;
	v_recno text;
	usedep double precision;
	anyreference text;	
	installment double precision;
BEGIN
-----------------------------------------------------
	--- fnd date in deposit which to be used
	if paytype = 1 
	then 
		recno:=gen_rec_no(usedate);
	else 	
		select into usevat  "TypePay"."UseVat" from "TypePay" where "TypePay"."TypeID"=paytype;
		if usevat=true then
			recno:=gen_rec_no(usedate);
		else
			recno:=gen_n_no(usedate);	
		end if;	
	end if;
	
	----  end for find date

------------------------------------------------------
	remainamt:=0;
	usedep:=useamt;
	for temdpremain in select * from "VDepositRemain" where "IDNO" = idno loop
		if temdpremain.remain is null then 
			remainamt:=temdpremain."O_MONEY";
		else
			remainamt:= temdpremain.remain;
		end if;
		
		if usedep > remainamt then

			k_recno:=gen_k_no(usedate);
			insert into "FOtherpay" values(idno,usedate,k_recno,-remainamt,299,'CA',current_date,'DEP',false,temdpremain."O_RECEIPT",recno);			usedep:=usedep-remainamt;
			
		else ---  usedep <= remainamt 
			
			k_recno:=gen_k_no(usedate);
			insert into "FOtherpay" values(idno,usedate,k_recno,-usedep,299,'CA',current_date,'DEP',false,temdpremain."O_RECEIPT",recno);
			usedep:=0;
			
		end if;

		if usedep = 0 then 
			exit;
		end if;
	end loop;
	

	-----------  use deposit to  any payment ---------------

	if paytype=1 then	
		select into pmonth,pvat,firstdate,custyear,ptotal  "Fp"."P_MONTH","Fp"."P_VAT","Fp"."P_FDATE","Fp"."P_CustByYear","Fp"."P_TOTAL" 
		from "Fp" where "Fp"."IDNO"= idno;
		
		timetopay := (useamt+discount)/(pmonth+pvat);
				
		select count(*) into dueno from "Fr" where "Fr"."IDNO" = idno and "Fr"."Cancel"=false and "Fr"."R_DueNo"<99 and "Fr"."R_DueNo">0;
		for j in 1..timetopay loop
			dueno:=dueno+1;
			insert into "Fr" values (idno,dueno,recno,usedate,pmonth,'CA',current_date,'DEP',false,null,null,custyear);

			if dueno = ptotal then
				update "Fp" set "P_ACCLOSE"=true , "P_CLDATE"=usedate ,"P_SL"=discount where "Fp"."IDNO"=idno;
			end if;

			----------------check vat print out already
			duedate := firstdate;
			select into  paidstate "FVat"."Paid_Status"  from "FVat" where "FVat"."IDNO"=idno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
			if found then
				update "FVat" set "Paid_Status" = true  where "FVat"."IDNO"=idno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
			else
				for i in 1..dueno-1 loop
					duedate := firstdate+ (i||'month')::interval;
				end loop;
		
				if duedate <= usedate  then
					temvatno := gen_vat_no(duedate);
				else
					if paybefore = false then 
						temvatno := gen_vat_no(usedate);
						paybefore:= true;
					end if;
					duedate := usedate;
				end if;
	
				insert into "FVat" values (idno,dueno,temvatno,duedate,pvat,current_date,true,false,null);
			end if;
			-------------------- end check vat ------------------
		end loop;----  for j
		outrecno :=  recno;
		
	else  ---- paytype <> 1
	
		select into usevat  "TypePay"."UseVat" from "TypePay" where "TypePay"."TypeID"=paytype;
		if usevat=true then
		
			select into custyear "Fp"."P_CustByYear" from "Fp" where "Fp"."IDNO"= idno;
			
			pvat := useamt - amt_before_vat(useamt);					
			insert into "Fr" values (idno,paytype,recno,usedate,amt_before_vat(useamt),'CA',current_date,'DEP',false,null,null);

			temvatno := gen_vat_no(usedate);
			insert into "FVat" values (idno,paytype,temvatno,usedate,round(cast(pvat as numeric),2),current_date,true,false,recno);

		else
			if trantoidno = '' then
				anyreference := post_any_reference(paytype,idno,useamt);
				insert into "FOtherpay" values (idno,usedate,recno,useamt,paytype,'CA',current_date,'DEP',false,null,anyreference);
			else
				insert into "FOtherpay" values (idno,usedate,recno,useamt,paytype,'CA',current_date,'DEP',false,null,trantoidno);
				k_recno:=gen_k_no(usedate);				
				insert into "FOtherpay" values (trantoidno,usedate,k_recno,useamt,200,'CA',current_date,'DEP',false,null,idno);			
			end if;
		end if;
		outrecno :=  recno;
	end if;

	return outrecno;

END;
$$;


ALTER FUNCTION public.select_deposit_remain(idno text, useamt double precision, usedate date, paytype integer, trantoidno text, discount double precision) OWNER TO dev;

--
-- TOC entry 567 (class 1255 OID 64482)
-- Dependencies: 15 1714
-- Name: show_discount_vfr(integer, text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION show_discount_vfr(dueno integer, idno text) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
	discount double precision:=0 ;
	total integer;
BEGIN
	select into discount "Fp"."P_SL" from "Fp" where "IDNO"=idno and "P_TOTAL"=dueno;
	if discount is null 
	then discount:=0;
	end if;
	return discount;
END;
$$;


ALTER FUNCTION public.show_discount_vfr(dueno integer, idno text) OWNER TO dev;

--
-- TOC entry 568 (class 1255 OID 64483)
-- Dependencies: 1714 15
-- Name: show_type_name(integer); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION show_type_name(dueno integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
	typename text;
BEGIN
	if dueno >=1 and dueno<99 then
		typename := 'งวดที่ '|| to_char(dueno,'09');
	else
		select into typename "TypePay"."TName" from "TypePay" where "TypePay"."TypeID"=dueno;
	end if;
	return typename;
END;
$$;


ALTER FUNCTION public.show_type_name(dueno integer) OWNER TO dev;

--
-- TOC entry 569 (class 1255 OID 64484)
-- Dependencies: 1714 15
-- Name: sum_deposit_afer_migrate(); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION sum_deposit_afer_migrate() RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
	temDBM "VDeposit_before_migrate"%ROWTYPE;
	rec_no text;

BEGIN

	for temDBM in select * from public."VDeposit_before_migrate" loop
	
		delete from "FOtherpay" where "IDNO" = temDBM."IDNO" and ("O_Type"=200 or "O_Type"=299);
		if temDBM.sum <> 0 then
		
			rec_no:= gen_k_no('2011-07-16');
			insert into "FOtherpay" values (temDBM."IDNO",'2011-07-16',rec_no,temDBM."sum",200,'CA','2011-07-16','DEP',false,null,null);
	
		end if;

	end loop;

	RETURN true;


END;$$;


ALTER FUNCTION public.sum_deposit_afer_migrate() OWNER TO dev;

--
-- TOC entry 570 (class 1255 OID 64485)
-- Dependencies: 1714 15
-- Name: time_not_sent_vat(text); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION time_not_sent_vat(idno text) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
	timenotsent integer;
BEGIN
	select count(*) into timenotsent from "VRemainPayment" where "IDNO"=idno and "V_Receipt" is null;
	return timenotsent;
END;
$$;


ALTER FUNCTION public.time_not_sent_vat(idno text) OWNER TO dev;

--
-- TOC entry 571 (class 1255 OID 64486)
-- Dependencies: 15 1714
-- Name: use_deposit_remain(text, double precision, integer, text, double precision); Type: FUNCTION; Schema: public; Owner: dev
--

CREATE FUNCTION use_deposit_remain(idno text, useamt double precision, paytype integer, trantoidno text, discount double precision) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE


-----------------------------------------
--------- input parameter ---------------
-----------------------------------------
-- idno is id of customer payment
-- useamt is amount of money which customerpay
-- paytype is type of pay (see in table "TypePay")
-- trantoidno is id of customer which sent amount to , use paytype=133 only
-- discount is amount of discount when customer close account , default=0  use paytype = 1 only
-----------------------------------------



	outrecno text:='';
	
	pmonth double precision;
	pvat double precision;
	firstdate date;
	ptotal integer;
	timetopay integer;
	dueno integer;
	recdate date;
	duedate date;
	paidstate boolean;
	temvatno text;
	paybefore boolean:=false;
	usevat boolean;
	custyear integer;
	
	temdpremain "VDepositRemain"%ROWTYPE;
	remainamt double precision;
	k_recno text;
	recno text;
	v_recno text;
	usedep double precision;
	anyreference text;	
	installment double precision;
BEGIN
-----------------------------------------------------
	--- fnd date in deposit which to be used

	installment := 0;
	if paytype = 1 then
	
		select into pmonth,pvat,firstdate,custyear,ptotal  "Fp"."P_MONTH","Fp"."P_VAT","Fp"."P_FDATE","Fp"."P_CustByYear","Fp"."P_TOTAL" 
		from "Fp" where "Fp"."IDNO"= idno;
		installment := pmonth+pvat;
		remainamt:=0;
		for temdpremain in select * from "VDepositRemain" where "IDNO"=idno  loop
			if temdpremain.remain is null then 
				remainamt:=remainamt+temdpremain."O_MONEY";
			else
				remainamt:= remainamt+temdpremain.remain;
			end if;	
			if useamt <= remainamt then
				recdate := temdpremain."O_DATE";
			end if;
			if remainamt >= installment then
				exit;
			end if;
		end loop;
		recno:=gen_rec_no(recdate);
		
	else ---------  paytype # 1

		remainamt:=0;
		for temdpremain in select * from "VDepositRemain" where "IDNO"=idno loop
			if temdpremain.remain is null then 
				remainamt:=remainamt+temdpremain."O_MONEY";
			else
				remainamt:= remainamt+temdpremain.remain;
			end if;	
			if useamt <= remainamt then
				recdate := temdpremain."O_DATE";
				exit;
			end if;
		end loop;
		
		select into usevat  "TypePay"."UseVat" from "TypePay" where "TypePay"."TypeID"=paytype;
		if usevat=true then
			recno:=gen_rec_no(recdate);
		else
			recno:=gen_n_no(recdate);	
		end if;	
	end if;
	
	----  end for find date

------------------------------------------------------
	remainamt:=0;
	usedep:=useamt;
	for temdpremain in select * from "VDepositRemain" where "IDNO" = idno loop
		if temdpremain.remain is null then 
			remainamt:=temdpremain."O_MONEY";
		else
			remainamt:= temdpremain.remain;
		end if;
		
		if usedep > remainamt then
			k_recno:=gen_k_no(recdate);
			insert into "FOtherpay" values(idno,recdate,k_recno,-remainamt,299,'CA',current_date,'DEP',false,temdpremain."O_RECEIPT",recno);
			usedep:=usedep-remainamt;
		else ---  useamt <= remainamt 
			
			k_recno:=gen_k_no(recdate);
			insert into "FOtherpay" values(idno,recdate,k_recno,-usedep,299,'CA',current_date,'DEP',false,temdpremain."O_RECEIPT",recno);
			usedep:=0;
		end if;
		
		if usedep = 0 then 
			exit;
		end if;
	end loop;
	

	-----------  use deposit to  any payment ---------------

	if paytype=1 then

		timetopay := (useamt+discount)/(pmonth+pvat);
				
		select count(*) into dueno from "Fr" where "Fr"."IDNO" = idno and "Fr"."Cancel"=false and "Fr"."R_DueNo"<99 and "Fr"."R_DueNo">0;
		for j in 1..timetopay loop
			dueno:=dueno+1;
			insert into "Fr" values (idno,dueno,recno,recdate,pmonth,'CA',current_date,'DEP',false,null,null,custyear);

			if dueno = ptotal then
				update "Fp" set "P_ACCLOSE"=true , "P_CLDATE"=recdate ,"P_SL"=discount where "Fp"."IDNO"=idno;
			end if;

			----------------check vat print out already
			duedate := firstdate;
			select into  paidstate "FVat"."Paid_Status"  from "FVat" where "FVat"."IDNO"=idno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
			if found then
				update "FVat" set "Paid_Status" = true  where "FVat"."IDNO"=idno and "FVat"."V_DueNo"=dueno and "FVat"."Cancel"=false;
			else
				for i in 1..dueno-1 loop
					duedate := firstdate+ (i||'month')::interval;
				end loop;
		
				if duedate <= recdate  then
					temvatno := gen_vat_no(duedate);
				else
					if paybefore = false then 
						temvatno := gen_vat_no(recdate);
						paybefore:= true;
					end if;
					duedate := recdate;
				end if;
	
				insert into "FVat" values (idno,dueno,temvatno,duedate,pvat,current_date,true,false,null);
			end if;
			-------------------- end check vat ------------------
		end loop;----  for j
		outrecno :=  recno;
		
	else  ---- paytype <> 1
	
		select into usevat  "TypePay"."UseVat" from "TypePay" where "TypePay"."TypeID"=paytype;
		if usevat=true then
		
			select into custyear "Fp"."P_CustByYear" from "Fp" where "Fp"."IDNO"= idno;
			
			pvat := useamt - amt_before_vat(useamt);					
			insert into "Fr" values (idno,paytype,recno,recdate,amt_before_vat(useamt),'CA',current_date,'DEP',false,null,null);

			temvatno := gen_vat_no(recdate);
			insert into "FVat" values (idno,paytype,temvatno,recdate,round(cast(pvat as numeric),2),current_date,true,false,recno);

		else
			if trantoidno = '' then
				anyreference := post_any_reference(paytype,idno,useamt);
				insert into "FOtherpay" values (idno,recdate,recno,useamt,paytype,'CA',current_date,'DEP',false,null,anyreference);
			else
				insert into "FOtherpay" values (idno,recdate,recno,useamt,paytype,'CA',current_date,'DEP',false,null,trantoidno);
				k_recno:=gen_k_no(recdate);				
				insert into "FOtherpay" values (trantoidno,recdate,k_recno,useamt,200,'CA',current_date,'DEP',false,null,idno);			
			end if;
		end if;
		outrecno :=  recno;
	end if;

	return outrecno;
END;
$$;


ALTER FUNCTION public.use_deposit_remain(idno text, useamt double precision, paytype integer, trantoidno text, discount double precision) OWNER TO dev;

SET search_path = account, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 170 (class 1259 OID 64488)
-- Dependencies: 3123 3124 6
-- Name: AcTable; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "AcTable" (
    "AcID" character varying(6) NOT NULL,
    "AcName" character varying(100),
    "AcType" character varying(6),
    "Status" character varying(6),
    "Delable" boolean DEFAULT false,
    "ShowOnFS" boolean DEFAULT true
);


ALTER TABLE account."AcTable" OWNER TO dev;

--
-- TOC entry 171 (class 1259 OID 64493)
-- Dependencies: 6
-- Name: AccCash; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "AccCash" (
    "AcID" character varying(6) NOT NULL
);


ALTER TABLE account."AccCash" OWNER TO dev;

--
-- TOC entry 172 (class 1259 OID 64496)
-- Dependencies: 3125 3126 6
-- Name: AccountBookDetail; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "AccountBookDetail" (
    auto_id integer NOT NULL,
    autoid_abh bigint,
    "AcID" character varying(6),
    "AmtDr" double precision DEFAULT 0,
    "AmtCr" double precision DEFAULT 0,
    "RefID" character varying(25)
);


ALTER TABLE account."AccountBookDetail" OWNER TO dev;

--
-- TOC entry 173 (class 1259 OID 64501)
-- Dependencies: 6 172
-- Name: AccountBookDetail_auto_id_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "AccountBookDetail_auto_id_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."AccountBookDetail_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4030 (class 0 OID 0)
-- Dependencies: 173
-- Name: AccountBookDetail_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: account; Owner: dev
--

ALTER SEQUENCE "AccountBookDetail_auto_id_seq" OWNED BY "AccountBookDetail".auto_id;


--
-- TOC entry 174 (class 1259 OID 64503)
-- Dependencies: 3128 6
-- Name: AccountBookHead; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "AccountBookHead" (
    auto_id integer NOT NULL,
    type_acb character varying(3),
    acb_id character varying(12),
    acb_date date,
    acb_detail text,
    sub_type character(1),
    ref_id character varying(25),
    cancel boolean DEFAULT false
);


ALTER TABLE account."AccountBookHead" OWNER TO dev;

--
-- TOC entry 4031 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN "AccountBookHead".sub_type; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "AccountBookHead".sub_type IS 'seperate book ''A'' or ''B''';


--
-- TOC entry 4032 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN "AccountBookHead".ref_id; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "AccountBookHead".ref_id IS 'เลขที่อ้างอิงซึ่งธุรกรรมนั้นอาจลงรายการมากกว่า 1 ชุด
ซึ่งต้องใช้อ้างอิงกัน ';


--
-- TOC entry 175 (class 1259 OID 64510)
-- Dependencies: 6 174
-- Name: AccountBookHead_auto_id_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "AccountBookHead_auto_id_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."AccountBookHead_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4033 (class 0 OID 0)
-- Dependencies: 175
-- Name: AccountBookHead_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: account; Owner: dev
--

ALTER SEQUENCE "AccountBookHead_auto_id_seq" OWNED BY "AccountBookHead".auto_id;


--
-- TOC entry 176 (class 1259 OID 64512)
-- Dependencies: 6
-- Name: BookBuy; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "BookBuy" (
    bh_id bigint NOT NULL,
    buy_from text,
    buy_receiptno text,
    pay_buy text,
    to_hp_id character varying(25)
);


ALTER TABLE account."BookBuy" OWNER TO dev;

--
-- TOC entry 177 (class 1259 OID 64518)
-- Dependencies: 3130 6
-- Name: ChequeAcc; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "ChequeAcc" (
    "AcID" character varying(20) NOT NULL,
    "BankName" character varying(50),
    "BankBranch" character varying(50),
    "AcType" character varying(1) DEFAULT '1'::character varying
);


ALTER TABLE account."ChequeAcc" OWNER TO dev;

--
-- TOC entry 4034 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN "ChequeAcc"."AcType"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "ChequeAcc"."AcType" IS '1 กระแสรายวัน
2 ออมทรัพย์';


--
-- TOC entry 178 (class 1259 OID 64522)
-- Dependencies: 3131 3132 6
-- Name: ChequeOfCompany; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "ChequeOfCompany" (
    "AcID" character varying(20) NOT NULL,
    "ChqID" character varying(10) NOT NULL,
    "DateOnChq" date,
    "Amount" double precision DEFAULT 0,
    "TypeOfPay" character(1),
    "DoDate" date,
    "PayTo" character varying(100),
    cancel boolean DEFAULT false,
    remark text
);


ALTER TABLE account."ChequeOfCompany" OWNER TO dev;

--
-- TOC entry 4035 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN "ChequeOfCompany"."TypeOfPay"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "ChequeOfCompany"."TypeOfPay" IS '1 is payee only
2 is in account
3 is cash';


--
-- TOC entry 179 (class 1259 OID 64530)
-- Dependencies: 3133 3134 3135 3136 6
-- Name: CostOfCar; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "CostOfCar" (
    "IDNO" character varying(25) NOT NULL,
    vd_vat_date date,
    bill_no character varying(15),
    vat_no character varying(15),
    cost_of_car double precision DEFAULT 0,
    vat_of_cost double precision DEFAULT 0,
    venderid character varying(10),
    status_pay boolean DEFAULT false,
    status_approve boolean DEFAULT false,
    pay_ref character varying(25)
);


ALTER TABLE account."CostOfCar" OWNER TO dev;

--
-- TOC entry 180 (class 1259 OID 64537)
-- Dependencies: 6
-- Name: FormulaAcc; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "FormulaAcc" (
    auto_id integer NOT NULL,
    fm_id character varying(5) NOT NULL,
    accno character varying(4),
    drcr bit(1)
);


ALTER TABLE account."FormulaAcc" OWNER TO dev;

--
-- TOC entry 4036 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN "FormulaAcc".drcr; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "FormulaAcc".drcr IS '1=Dr
0=Cr';


--
-- TOC entry 181 (class 1259 OID 64540)
-- Dependencies: 6 180
-- Name: FormulaAcc_auto_id_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "FormulaAcc_auto_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."FormulaAcc_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4037 (class 0 OID 0)
-- Dependencies: 181
-- Name: FormulaAcc_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: account; Owner: dev
--

ALTER SEQUENCE "FormulaAcc_auto_id_seq" OWNED BY "FormulaAcc".auto_id;


--
-- TOC entry 182 (class 1259 OID 64542)
-- Dependencies: 6
-- Name: FormulaID; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "FormulaID" (
    fm_id character varying(5) NOT NULL,
    fm_name character varying(100),
    type_acb character varying(3)
);


ALTER TABLE account."FormulaID" OWNER TO dev;

--
-- TOC entry 183 (class 1259 OID 64545)
-- Dependencies: 6
-- Name: IntAccDetail_auto_id_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "IntAccDetail_auto_id_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."IntAccDetail_auto_id_seq" OWNER TO dev;

--
-- TOC entry 184 (class 1259 OID 64547)
-- Dependencies: 3138 3139 3140 6
-- Name: IntAccDetail; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "IntAccDetail" (
    auto_id integer DEFAULT nextval('"IntAccDetail_auto_id_seq"'::regclass) NOT NULL,
    autoid_abh bigint,
    "AcID" character varying(10),
    "AmtDr" double precision DEFAULT 0,
    "AmtCr" double precision DEFAULT 0,
    "RefID" character varying(25)
);


ALTER TABLE account."IntAccDetail" OWNER TO dev;

--
-- TOC entry 185 (class 1259 OID 64553)
-- Dependencies: 6
-- Name: IntAccHead_auto_id_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "IntAccHead_auto_id_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."IntAccHead_auto_id_seq" OWNER TO dev;

--
-- TOC entry 186 (class 1259 OID 64555)
-- Dependencies: 3141 3142 6
-- Name: IntAccHead; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "IntAccHead" (
    auto_id integer DEFAULT nextval('"IntAccHead_auto_id_seq"'::regclass) NOT NULL,
    type_acb character varying(3),
    acb_id character varying(12),
    acb_date date,
    acb_detail text,
    sub_type character(1),
    ref_id character varying(25),
    cancel boolean DEFAULT false
);


ALTER TABLE account."IntAccHead" OWNER TO dev;

--
-- TOC entry 187 (class 1259 OID 64563)
-- Dependencies: 3143 6
-- Name: PayID; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "PayID" (
    monthid date NOT NULL,
    payid smallint DEFAULT 0
);


ALTER TABLE account."PayID" OWNER TO dev;

--
-- TOC entry 188 (class 1259 OID 64567)
-- Dependencies: 3144 3145 3146 6
-- Name: PayToCar; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "PayToCar" (
    payid character varying(15) NOT NULL,
    dodate date,
    cash double precision DEFAULT 0,
    "CQBank" character varying(15),
    "CQID" character varying(15),
    "CQDate" date,
    "CQAmt" double precision DEFAULT 0,
    "Cancel" boolean DEFAULT false NOT NULL,
    "Remark" text,
    idmaker character varying(10),
    idauthority character varying(10)
);


ALTER TABLE account."PayToCar" OWNER TO dev;

--
-- TOC entry 189 (class 1259 OID 64576)
-- Dependencies: 3147 3148 6
-- Name: RptVatBuy; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "RptVatBuy" (
    v_date date NOT NULL,
    acb_id character varying(15) NOT NULL,
    vat_no character varying(15),
    vd_name character varying(150),
    value_cost double precision DEFAULT 0,
    vat_cost double precision DEFAULT 0
);


ALTER TABLE account."RptVatBuy" OWNER TO dev;

--
-- TOC entry 190 (class 1259 OID 64581)
-- Dependencies: 3149 3150 3151 3152 3153 3154 3155 3156 6
-- Name: RunningNo; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "RunningNo" (
    "RunningDate" date NOT NULL,
    "GJ" integer DEFAULT 0,
    "RC" integer DEFAULT 0,
    "PC" integer DEFAULT 0,
    "VR" integer DEFAULT 0,
    "VP" integer DEFAULT 0,
    "AJ" integer DEFAULT 0,
    "AP" integer DEFAULT 0,
    "IGJ" integer DEFAULT 0
);


ALTER TABLE account."RunningNo" OWNER TO dev;

--
-- TOC entry 191 (class 1259 OID 64592)
-- Dependencies: 3074 6
-- Name: VAccountBook; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "VAccountBook" AS
    SELECT h.auto_id, h.acb_date, h.type_acb, h.acb_id, h.acb_detail, h.ref_id, h.cancel, d."AcID", a."AcName", d."AmtDr", d."AmtCr", d."RefID" FROM "AccountBookDetail" d, "AccountBookHead" h, "AcTable" a WHERE (((d.autoid_abh = h.auto_id) AND ((d."AcID")::text = (a."AcID")::text)) AND (h.cancel = false)) ORDER BY h.acb_date, d.autoid_abh, d.auto_id;


ALTER TABLE account."VAccountBook" OWNER TO dev;

--
-- TOC entry 192 (class 1259 OID 64596)
-- Dependencies: 6
-- Name: debtbalance; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE debtbalance (
    acclosedate date NOT NULL,
    idno character varying(25) NOT NULL,
    cusid character varying(12) NOT NULL,
    custyear smallint,
    monthly double precision,
    totaldue smallint,
    notpaid smallint,
    vatpayready double precision
);


ALTER TABLE account.debtbalance OWNER TO dev;

--
-- TOC entry 193 (class 1259 OID 64599)
-- Dependencies: 3075 6
-- Name: VDebtBalance; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "VDebtBalance" AS
    SELECT debtbalance.acclosedate, debtbalance.custyear, debtbalance.idno, public.customer_name((debtbalance.cusid)::text) AS customer_name, ((debtbalance.notpaid)::double precision * debtbalance.monthly) AS remain, debtbalance.vatpayready FROM debtbalance;


ALTER TABLE account."VDebtBalance" OWNER TO dev;

--
-- TOC entry 194 (class 1259 OID 64603)
-- Dependencies: 3076 6
-- Name: VIntAccount; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "VIntAccount" AS
    SELECT h.auto_id, h.acb_date, h.type_acb, h.acb_id, h.acb_detail, d."AcID", a."AcName", d."AmtDr", d."AmtCr", d."RefID" FROM "IntAccDetail" d, "IntAccHead" h, "AcTable" a WHERE ((d.autoid_abh = h.auto_id) AND ((d."AcID")::text = (a."AcID")::text)) ORDER BY h.acb_date, d.autoid_abh, d.auto_id;


ALTER TABLE account."VIntAccount" OWNER TO dev;

--
-- TOC entry 195 (class 1259 OID 64607)
-- Dependencies: 3077 6
-- Name: VIntPayEachDay; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "VIntPayEachDay" AS
    SELECT "VIntAccount".acb_date, "VIntAccount"."RefID", "VIntAccount"."AcID", "VIntAccount"."AcName", sum("VIntAccount"."AmtDr") AS dr, sum("VIntAccount"."AmtCr") AS cr, (sum("VIntAccount"."AmtDr") - sum("VIntAccount"."AmtCr")) AS balance FROM "VIntAccount" GROUP BY "VIntAccount".acb_date, "VIntAccount"."RefID", "VIntAccount"."AcID", "VIntAccount"."AcName";


ALTER TABLE account."VIntPayEachDay" OWNER TO dev;

--
-- TOC entry 196 (class 1259 OID 64611)
-- Dependencies: 3157 3158 3159 3160 6
-- Name: effsoyaddcom; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE effsoyaddcom (
    acclosedate date NOT NULL,
    idno character varying(25) NOT NULL,
    cusid character varying(12) NOT NULL,
    custyear smallint,
    paid smallint,
    overdue smallint,
    nextydue smallint,
    otherydue smallint,
    totaldue smallint,
    monthly double precision,
    aroverdue double precision,
    arnextydue double precision,
    arotherydue double precision,
    artotal double precision,
    rlpreviousy double precision,
    rltothisy double precision,
    rlpayreal double precision,
    rlnexty double precision,
    rlall double precision,
    rlthisy double precision,
    uroverdue double precision,
    urnexty double precision,
    urothery double precision,
    urtotal double precision,
    tranid character varying(25),
    duenoac smallint,
    comlastyear double precision,
    comaccthisyear double precision,
    comnextyear double precision,
    aroutstanding double precision DEFAULT 0,
    aroutafterguarantee double precision DEFAULT 0,
    writeoffrate smallint DEFAULT 0,
    backupwriteoff double precision DEFAULT 0
);


ALTER TABLE account.effsoyaddcom OWNER TO dev;

--
-- TOC entry 4038 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN effsoyaddcom.aroutstanding; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN effsoyaddcom.aroutstanding IS 'ยอดค่างวดที่ค้างทั้งหมด  มาจาก arnextydue+arotherydue';


--
-- TOC entry 4039 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN effsoyaddcom.aroutafterguarantee; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN effsoyaddcom.aroutafterguarantee IS 'ยอดหนี้(ค่างวดที่เหลือ) หลังหักหลักประกัน 80% มาจาก aroutstanding*20%';


--
-- TOC entry 4040 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN effsoyaddcom.writeoffrate; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN effsoyaddcom.writeoffrate IS 'อัตราการตั้งสำรองหนี้สูญเป็น% มาจาก จำนวนงวดที่ค้างกี่งวด';


--
-- TOC entry 4041 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN effsoyaddcom.backupwriteoff; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN effsoyaddcom.backupwriteoff IS 'เงินสำรองหนี้สูญมาจาก aroutafterguaratee * writeoffrate';


--
-- TOC entry 197 (class 1259 OID 64618)
-- Dependencies: 3078 6
-- Name: VSOYEndYear; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "VSOYEndYear" AS
    SELECT effsoyaddcom.acclosedate, effsoyaddcom.custyear, effsoyaddcom.idno, public.customer_name((effsoyaddcom.cusid)::text) AS customer_name, effsoyaddcom.rlpreviousy, effsoyaddcom.overdue, effsoyaddcom.paid, (effsoyaddcom.paid + effsoyaddcom.overdue) AS mustpay, effsoyaddcom.duenoac AS effpay, effsoyaddcom.rlthisy, effsoyaddcom.rltothisy, effsoyaddcom.urtotal AS rlremain, effsoyaddcom.rlall, effsoyaddcom.aroutstanding, effsoyaddcom.urtotal, effsoyaddcom.aroutafterguarantee, effsoyaddcom.writeoffrate, effsoyaddcom.backupwriteoff FROM effsoyaddcom;


ALTER TABLE account."VSOYEndYear" OWNER TO dev;

SET search_path = public, pg_catalog;

--
-- TOC entry 198 (class 1259 OID 64622)
-- Dependencies: 3161 15
-- Name: FVat; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FVat" (
    "IDNO" character varying(25) NOT NULL,
    "V_DueNo" smallint NOT NULL,
    "V_Receipt" character varying(12) NOT NULL,
    "V_Date" date NOT NULL,
    "VatValue" double precision,
    "V_PrnDate" date,
    "Paid_Status" boolean,
    "Cancel" boolean DEFAULT false,
    "V_memo" text
);


ALTER TABLE public."FVat" OWNER TO dev;

--
-- TOC entry 199 (class 1259 OID 64629)
-- Dependencies: 3162 3163 3164 3165 3166 3167 3168 3169 3170 3171 3172 3173 3174 3175 3176 3177 3178 3179 3180 3181 3182 3183 3184 15
-- Name: Fp; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fp" (
    "IDNO" character varying(25) NOT NULL,
    "TranIDRef1" character varying(15),
    "TranIDRef2" character varying(15),
    "PayCon" character varying(5),
    "AccType" character varying(5),
    "P_STDATE" date,
    "P_MONTH" double precision DEFAULT 0,
    "P_VAT" double precision DEFAULT 0,
    "P_TOTAL" smallint DEFAULT 0,
    "P_DOWN" double precision DEFAULT 0,
    "P_VatOfDown" double precision DEFAULT 0,
    "P_BEGIN" double precision DEFAULT 0,
    "P_BEGINX" double precision DEFAULT 0,
    "P_FDATE" date,
    "P_OP_PRE" double precision,
    "P_CLDATE" date,
    "P_SL" double precision DEFAULT 0,
    "P_LAWERFEEAmt" double precision DEFAULT 0,
    "P_RECONTRACT_AMT" double precision DEFAULT 0,
    "P_QuestProcess" character(1),
    "P_QuestProcess_AMT" double precision DEFAULT 0,
    "P_TransferFee" double precision DEFAULT 0,
    "P_TransferIDNO" character varying(25),
    "P_StopVatDate" date,
    "P_CtrlBy" character varying(10),
    "P_CustByYear" smallint,
    "PayType" character varying(10),
    "WriteOffDate" date,
    "EffRate" double precision DEFAULT 0,
    "Comm" double precision DEFAULT 0,
    "PathPDFFile" character varying(100),
    "ComeFrom" character varying(100),
    "P_ACCLOSE" boolean DEFAULT false NOT NULL,
    "P_StopVat" boolean DEFAULT false NOT NULL,
    "WriteOff" boolean DEFAULT false NOT NULL,
    "P_LAWERFEE" boolean DEFAULT false NOT NULL,
    "P_TAXABLE" boolean DEFAULT false NOT NULL,
    "LockContact" boolean DEFAULT false NOT NULL,
    "CusID" character varying(12) NOT NULL,
    asset_type smallint DEFAULT 1 NOT NULL,
    asset_id character varying(12) DEFAULT NULL::character varying,
    repo boolean DEFAULT false NOT NULL,
    repo_date date,
    "creditType" character varying(35)
);


ALTER TABLE public."Fp" OWNER TO dev;

--
-- TOC entry 4042 (class 0 OID 0)
-- Dependencies: 199
-- Name: TABLE "Fp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "Fp" IS '

';


--
-- TOC entry 4043 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN "Fp"."Comm"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp"."Comm" IS 'ค่านายหน้าในการขายรถ
';


--
-- TOC entry 4044 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN "Fp"."ComeFrom"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp"."ComeFrom" IS 'ซื้อรถคันนี้มาจากที่ไหน';


--
-- TOC entry 4045 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN "Fp".asset_id; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp".asset_id IS 'id of any asset  type1:taxi =tax00001 , type2:gas = gas00001';


--
-- TOC entry 4046 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN "Fp".repo; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp".repo IS 'รถยึดคืน  = true  ';


--
-- TOC entry 4047 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN "Fp".repo_date; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp".repo_date IS 'วันที่ยึดรถ';


--
-- TOC entry 200 (class 1259 OID 64655)
-- Dependencies: 3185 15
-- Name: Fr; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fr" (
    "IDNO" character varying(25) NOT NULL,
    "R_DueNo" smallint NOT NULL,
    "R_Receipt" character varying(10) NOT NULL,
    "R_Date" date,
    "R_Money" double precision,
    "R_Bank" character varying(10),
    "R_Prndate" date,
    "PayType" character varying(10),
    "Cancel" boolean DEFAULT false,
    "R_memo" text,
    "RefAnyID" character varying(25),
    "CustYear" smallint
);


ALTER TABLE public."Fr" OWNER TO dev;

--
-- TOC entry 4048 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN "Fr"."R_DueNo"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fr"."R_DueNo" IS '0=down , 1..99 = ค่างวด  100ขึ้นไปดูที่ TypePay';


--
-- TOC entry 4049 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN "Fr"."RefAnyID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fr"."RefAnyID" IS 'id สำหรับอ้างอิงกับการชำระอื่นๆ  เช่น id ของประกันภัย  ';


--
-- TOC entry 201 (class 1259 OID 64662)
-- Dependencies: 3079 15
-- Name: VFrEachDay; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VFrEachDay" AS
    SELECT "Fr"."R_Date", "Fr"."R_Receipt", "Fr"."IDNO", customer_name(("Fp"."CusID")::text) AS full_name, asset_name(("Fp".asset_type)::integer, ("Fp".asset_id)::text) AS assetname, asset_regis(("Fp".asset_type)::integer, ("Fp".asset_id)::text) AS regis, "Fr"."R_Money" AS value, "FVat"."VatValue" AS vat, ("Fr"."R_Money" + "FVat"."VatValue") AS money, show_type_name(("Fr"."R_DueNo")::integer) AS typepay_name, "Fr"."PayType", "Fr"."R_Prndate", "Fr"."R_Bank", "Fr"."R_memo", show_discount_vfr(("Fr"."R_DueNo")::integer, ("Fr"."IDNO")::text) AS discount, "Fr"."CustYear" FROM "Fr", "Fp", "FVat" WHERE (((((("Fr"."IDNO")::text = ("Fp"."IDNO")::text) AND (("Fr"."IDNO")::text = ("FVat"."IDNO")::text)) AND ("Fr"."R_DueNo" = "FVat"."V_DueNo")) AND ("Fr"."Cancel" = false)) AND ("FVat"."Cancel" = false)) UNION ALL SELECT "Fr"."R_Date", "Fr"."R_Receipt", "Fr"."IDNO", customer_name(("Fp"."CusID")::text) AS full_name, asset_name(("Fp".asset_type)::integer, ("Fp".asset_id)::text) AS assetname, asset_regis(("Fp".asset_type)::integer, ("Fp".asset_id)::text) AS regis, "Fr"."R_Money" AS value, 0 AS vat, "Fr"."R_Money" AS money, show_type_name(("Fr"."R_DueNo")::integer) AS typepay_name, "Fr"."PayType", "Fr"."R_Prndate", "Fr"."R_Bank", "Fr"."R_memo", show_discount_vfr(("Fr"."R_DueNo")::integer, ("Fr"."IDNO")::text) AS discount, "Fr"."CustYear" FROM "Fr", "Fp" WHERE (((("Fr"."IDNO")::text = ("Fp"."IDNO")::text) AND ("Fr"."R_DueNo" > 100)) AND ("Fr"."Cancel" = false));


ALTER TABLE public."VFrEachDay" OWNER TO dev;

SET search_path = account, pg_catalog;

--
-- TOC entry 202 (class 1259 OID 64667)
-- Dependencies: 3080 6
-- Name: VSumRecCustYear; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "VSumRecCustYear" AS
    SELECT "VFrEachDay"."R_Date", "VFrEachDay"."CustYear", (sum("VFrEachDay".value) - sum("VFrEachDay".discount)) AS tot_val, sum("VFrEachDay".vat) AS tot_vat FROM public."VFrEachDay" GROUP BY "VFrEachDay"."R_Date", "VFrEachDay"."CustYear" ORDER BY "VFrEachDay"."R_Date", "VFrEachDay"."CustYear";


ALTER TABLE account."VSumRecCustYear" OWNER TO dev;

--
-- TOC entry 203 (class 1259 OID 64671)
-- Dependencies: 3081 6
-- Name: VSumRecR_Bank; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "VSumRecR_Bank" AS
    SELECT "VFrEachDay"."R_Date", "VFrEachDay"."R_Bank", (sum("VFrEachDay".value) - sum("VFrEachDay".discount)) AS tot_val, sum("VFrEachDay".vat) AS tot_vat FROM public."VFrEachDay" GROUP BY "VFrEachDay"."R_Date", "VFrEachDay"."R_Bank" ORDER BY "VFrEachDay"."R_Date", "VFrEachDay"."R_Bank";


ALTER TABLE account."VSumRecR_Bank" OWNER TO dev;

--
-- TOC entry 446 (class 1259 OID 69546)
-- Dependencies: 6
-- Name: thcap_mg_interest; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_mg_interest (
    "mgInterestID" character varying(30) NOT NULL,
    "contractID" character varying(30) NOT NULL,
    "intGenStamp" timestamp without time zone,
    "intSerial" integer NOT NULL,
    "intStartDate" date NOT NULL,
    "intEndDate" date NOT NULL,
    "intCurRate" numeric(15,2) NOT NULL,
    "intCurPrinciple" numeric(15,2) NOT NULL,
    "intAmtPerDay" numeric(30,15) NOT NULL,
    "intAmtByCurRate" numeric(15,2) NOT NULL,
    "intAmtPerDayRounded" numeric(15,2) NOT NULL,
    "intMethod" smallint NOT NULL,
    "intLocked" smallint
);


ALTER TABLE account.thcap_mg_interest OWNER TO dev;

--
-- TOC entry 4050 (class 0 OID 0)
-- Dependencies: 446
-- Name: TABLE thcap_mg_interest; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_mg_interest IS 'ดอกเบี้ยที่เกิดขึ้นของแต่ละสัญญา ในแต่ละวัน';


--
-- TOC entry 4051 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."contractID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."contractID" IS 'เลขที่สัญญา';


--
-- TOC entry 4052 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."intGenStamp"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."intGenStamp" IS 'วันเวลาที่ทำการ Generate ดอกเบี้ย';


--
-- TOC entry 4053 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."intSerial"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."intSerial" IS 'ลำดับของดอกเบี้ยที่ Gen ของสัญญานั้นๆ เช่น
วันที่แรกของสัญญานี้ ที่ gen -> 1
วันที่แรกของสัญญานี้ ที่ gen -> 2
วันที่แรกของสัญญานี้ ที่ gen -> 3
...';


--
-- TOC entry 4054 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."intStartDate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."intStartDate" IS 'วันที่เริ่มคิดดอกเบี้ย';


--
-- TOC entry 4055 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."intEndDate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."intEndDate" IS 'วันที่สิ้นสุดการคิดดอกเบี้ย';


--
-- TOC entry 4056 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."intCurRate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."intCurRate" IS 'อัตราดอกเบี้ยปัจจุบัน';


--
-- TOC entry 4057 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."intCurPrinciple"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."intCurPrinciple" IS 'เงินต้นที่นำมาคิดดอกเบี้ย';


--
-- TOC entry 4058 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."intAmtPerDay"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."intAmtPerDay" IS 'เงินดอกเบี้ยที่เกิดขึ้นใน 1 วันในช่วงดังกล่าวนี้';


--
-- TOC entry 4059 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."intAmtByCurRate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."intAmtByCurRate" IS 'ดอกเบี้ยที่คำนวณโดยอัตราดอกเบี้ยปัจจุบัน';


--
-- TOC entry 4060 (class 0 OID 0)
-- Dependencies: 446
-- Name: COLUMN thcap_mg_interest."intAmtPerDayRounded"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_interest."intAmtPerDayRounded" IS 'เงินดอกเบี้ยที่เกิดขึ้นใน 1 วันในช่วงดังกล่าวนี้ และถูกปัดให้อยู่ 2 ตำแหน่ง';


--
-- TOC entry 204 (class 1259 OID 64678)
-- Dependencies: 6
-- Name: thcap_mg_receipt_interest; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_mg_receipt_interest (
    "receiptID" character varying(30) NOT NULL,
    "intSerial" integer NOT NULL,
    "amtPerSerial" numeric(15,2)
);


ALTER TABLE account.thcap_mg_receipt_interest OWNER TO dev;

--
-- TOC entry 4061 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN thcap_mg_receipt_interest."receiptID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_receipt_interest."receiptID" IS 'เลขที่ใบเสร็จ';


--
-- TOC entry 4062 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN thcap_mg_receipt_interest."intSerial"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_receipt_interest."intSerial" IS 'ดอกเบี้ยช่วงที่';


--
-- TOC entry 4063 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN thcap_mg_receipt_interest."amtPerSerial"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_receipt_interest."amtPerSerial" IS 'จำนวนเงินที่จ่ายในช่วงดอกเบี้ยนี้';


--
-- TOC entry 205 (class 1259 OID 64681)
-- Dependencies: 3186 3187 3188 6
-- Name: thcap_receipt; Type: TABLE; Schema: account; Owner: devgroup; Tablespace: 
--

CREATE TABLE thcap_receipt (
    "receiptID" character varying(30) NOT NULL,
    "contractID" character varying(35) NOT NULL,
    "contractType" character varying(25) NOT NULL,
    "receiveDate" timestamp without time zone NOT NULL,
    "receiveAmt" numeric(15,2) NOT NULL,
    "receiveNetAmt" numeric(15,2) NOT NULL,
    "receiveDiscount" numeric(15,2) NOT NULL,
    "receiveTotalVAT" numeric(15,2) NOT NULL,
    "receiveTotalWHT" numeric(15,2) NOT NULL,
    "receiveNet" numeric(15,2),
    "paperWHTID" character varying(30),
    "receiptFlow" smallint DEFAULT 0 NOT NULL,
    "receiptStatus" smallint DEFAULT 1 NOT NULL,
    CONSTRAINT "checkWHT" CHECK (((("receiveTotalWHT" > 0.00) AND ("paperWHTID" IS NOT NULL)) OR (("receiveTotalWHT" = 0.00) AND ("paperWHTID" IS NULL))))
);


ALTER TABLE account.thcap_receipt OWNER TO devgroup;

--
-- TOC entry 4064 (class 0 OID 0)
-- Dependencies: 205
-- Name: TABLE thcap_receipt; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON TABLE thcap_receipt IS 'ข้อมูลใบเสร็จที่รับชำระคืนเงินกู้จำนอง โดยการ INSERT เข้า TABLE นี้
จะเป็นใบเสร็จที่เป็น ACTIVE แล้วเสมอ (ไม่ต้อง Approve อีก)

todo:
--------------------------------------
- ทำ foreign key';


--
-- TOC entry 4065 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."receiptID"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."receiptID" IS 'เลขที่ใบเสร็จรับเงิน';


--
-- TOC entry 4066 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."contractID"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."contractID" IS 'เลขที่สัญญาหลัก';


--
-- TOC entry 4067 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."receiveDate"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."receiveDate" IS 'วันเวลาที่รับชำระ';


--
-- TOC entry 4068 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."receiveAmt"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."receiveAmt" IS 'จำนวนเงินที่มองว่าได้รับชำระรวมทุกรายการ (ไม่รวม VAT)';


--
-- TOC entry 4069 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."receiveNetAmt"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."receiveNetAmt" IS 'จำนวนเงินที่รับชำระจริง (ไม่รวม VAT) - receiveNetAmt
จำนวนเงินที่มองว่าได้รับ (ไม่รวม VAT) - receiveAmt
จำนวนเงินที่เป็นส่วนลด - receiveDiscount
จำนวนเงินที่เป็นหัก ณ ที่จ่ายไว้ - receiveTotalWHT

จำนวนเงินที่รับในใบเสร็จ
receiveNet = receiveNetAmt + receiveTotalVAT

โดย
receiveNetAmt = receiveAmt - receiveDiscount

';


--
-- TOC entry 4070 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."receiveDiscount"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."receiveDiscount" IS 'ส่วนลด';


--
-- TOC entry 4071 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."receiveTotalVAT"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."receiveTotalVAT" IS 'จำนวนรวม VAT';


--
-- TOC entry 4072 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."receiveTotalWHT"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."receiveTotalWHT" IS 'จำนวนรวมภาษีหัก ณ ที่จ่าย';


--
-- TOC entry 4073 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."receiveNet"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."receiveNet" IS 'ยอดรับชำระจริงสุทธิ (รวม VAT) (ไม่หัก WHT ออกเพราะถือว่าได้เงินเหมือนกันแต่เป็นตั๋วแลกเงิน)';


--
-- TOC entry 4074 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."paperWHTID"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."paperWHTID" IS 'เอกสารหักภาษี ณ ที่จ่าย';


--
-- TOC entry 4075 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN thcap_receipt."receiptStatus"; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON COLUMN thcap_receipt."receiptStatus" IS 'สถานะใบเสร็จ
0-DELETED
1-ACTIVE';


--
-- TOC entry 4076 (class 0 OID 0)
-- Dependencies: 205
-- Name: CONSTRAINT "checkWHT" ON thcap_receipt; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON CONSTRAINT "checkWHT" ON thcap_receipt IS 'ตรวจสอบว่าถ้ามียอด WHT จะต้องมี paperID ของ WHT ด้วย';


--
-- TOC entry 452 (class 1259 OID 69678)
-- Dependencies: 3121 6
-- Name: V_thcap_mg_intReceipt; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "V_thcap_mg_intReceipt" AS
    SELECT thcap_mg_interest."mgInterestID", thcap_mg_interest."contractID", thcap_mg_interest."intStartDate", thcap_mg_interest."intEndDate", thcap_mg_interest."intCurRate", thcap_mg_interest."intCurPrinciple", thcap_mg_interest."intAmtByCurRate", thcap_mg_interest."intSerial", thcap_mg_receipt_interest."receiptID", thcap_receipt."receiveDate", thcap_mg_receipt_interest."amtPerSerial" FROM ((thcap_receipt RIGHT JOIN thcap_mg_receipt_interest ON ((((thcap_receipt."receiptID")::text = (thcap_mg_receipt_interest."receiptID")::text) AND (thcap_receipt."receiptStatus" = 1)))) RIGHT JOIN thcap_mg_interest ON (((thcap_mg_receipt_interest."intSerial" = thcap_mg_interest."intSerial") AND ((thcap_receipt."contractID")::text = (thcap_mg_interest."contractID")::text)))) ORDER BY thcap_mg_interest."contractID", thcap_mg_interest."intSerial", thcap_receipt."receiveDate";


ALTER TABLE account."V_thcap_mg_intReceipt" OWNER TO dev;

--
-- TOC entry 4078 (class 0 OID 0)
-- Dependencies: 452
-- Name: VIEW "V_thcap_mg_intReceipt"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON VIEW "V_thcap_mg_intReceipt" IS 'แสดงดอกเบี้ยที่เกิดขึ้นในช่วงต่างๆ และใบเสร็จทั้งหมดที่จ่ายในช่วงนั้นๆ และมีสถานะปกติ (receiptStatus=1)';


--
-- TOC entry 3983 (class 2606 OID 69588)
-- Dependencies: 446 446
-- Name: thcap_mg_interest_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_interest
    ADD CONSTRAINT thcap_mg_interest_pkey PRIMARY KEY ("mgInterestID");


--
-- TOC entry 451 (class 1259 OID 69673)
-- Dependencies: 3120 6
-- Name: V_thcap_mg_intReceiptSum; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "V_thcap_mg_intReceiptSum" AS
    SELECT thcap_mg_interest."mgInterestID", thcap_mg_interest."contractID", thcap_mg_interest."intStartDate", thcap_mg_interest."intEndDate", thcap_mg_interest."intCurRate", thcap_mg_interest."intCurPrinciple", thcap_mg_interest."intAmtByCurRate", thcap_mg_interest."intSerial", sum(thcap_mg_receipt_interest."amtPerSerial") AS "sumAmtPerSerial" FROM ((thcap_receipt RIGHT JOIN thcap_mg_receipt_interest ON ((((thcap_receipt."receiptID")::text = (thcap_mg_receipt_interest."receiptID")::text) AND (thcap_receipt."receiptStatus" = 1)))) RIGHT JOIN thcap_mg_interest ON (((thcap_mg_receipt_interest."intSerial" = thcap_mg_interest."intSerial") AND ((thcap_receipt."contractID")::text = (thcap_mg_interest."contractID")::text)))) GROUP BY thcap_mg_interest."mgInterestID" ORDER BY thcap_mg_interest."contractID", thcap_mg_interest."intSerial";


ALTER TABLE account."V_thcap_mg_intReceiptSum" OWNER TO dev;

--
-- TOC entry 4079 (class 0 OID 0)
-- Dependencies: 451
-- Name: VIEW "V_thcap_mg_intReceiptSum"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON VIEW "V_thcap_mg_intReceiptSum" IS 'แสดงดอกเบี้ยที่เกิดขึ้นในช่วงต่างๆ และใบเสร็จทั้งหมดที่จ่ายในช่วงนั้นๆ และมีสถานะปกติ (receiptStatus=1)';


--
-- TOC entry 441 (class 1259 OID 69107)
-- Dependencies: 3441 3442 3443 3444 3445 6
-- Name: thcap_invoice; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_invoice (
    "invoiceID" character varying(30) NOT NULL,
    "contractID" character varying(35) NOT NULL,
    "contractType" character varying(30) NOT NULL,
    "invoiceCompID" character varying(30) NOT NULL,
    "invoiceDate" date NOT NULL,
    "invoiceDueDate" date,
    "invoiceTypePay" character varying(10) NOT NULL,
    "invoiceTypePayRef" character varying(30) NOT NULL,
    "invoiceIDBefore" character varying(30),
    "invoiceDescription" character varying(200),
    "invoiceAmt" numeric(15,2) NOT NULL,
    "invoiceVATRate" numeric(6,2),
    "invoiceAmtVAT" numeric(15,2) NOT NULL,
    "invoiceWHTRate" numeric(6,2),
    "invoiceAmtWHT" numeric(15,2) NOT NULL,
    "invoiceIsSent" smallint DEFAULT 0 NOT NULL,
    "invoiceAmtLeft" numeric(15,2),
    "invoiceStatus" smallint DEFAULT 9 NOT NULL,
    CONSTRAINT "thcap_invoice_check_invAmtLeft_notAbvIniAmt" CHECK (("invoiceAmtLeft" <= "invoiceAmt")),
    CONSTRAINT "thcap_invoice_check_invAmtLeft_notNeg" CHECK (("invoiceAmtLeft" >= 0.00)),
    CONSTRAINT thcap_invoice_check_mg_wht CHECK (((((("contractType")::text = 'MG'::text) AND ("invoiceWHTRate" IS NULL)) AND ("invoiceAmtWHT" = (0)::numeric)) OR (("contractType")::text <> 'MG'::text)))
);


ALTER TABLE account.thcap_invoice OWNER TO dev;

--
-- TOC entry 4080 (class 0 OID 0)
-- Dependencies: 441
-- Name: TABLE thcap_invoice; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_invoice IS 'รายการ invoice';


--
-- TOC entry 4081 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceID" IS 'เลขที่ใบแจ้งหนี้';


--
-- TOC entry 4082 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."contractID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."contractID" IS 'เลขที่สัญญาหลัก';


--
-- TOC entry 4083 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceDate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceDate" IS 'วันที่ออกใบแจ้งหนี้';


--
-- TOC entry 4084 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceDueDate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceDueDate" IS 'วันที่ครบกำหนดชำระที่ต้องจ่าย';


--
-- TOC entry 4085 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceTypePay"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceTypePay" IS 'ประเภทการจ่าย';


--
-- TOC entry 4086 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceTypePayRef"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceTypePayRef" IS 'Reference ของการจ่ายหนี้บางชนิดเช่น ยอดผ่อนขั้นต่ำเดือน มกราคม 2011 (201101) หรือค่าติดตามทวงถาม 201101 หรืออื่นๆ';


--
-- TOC entry 4087 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceIDBefore"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceIDBefore" IS 'ยกมาจาก invoice ก่อนหน้าใด';


--
-- TOC entry 4088 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceDescription"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceDescription" IS 'Description ของรายการนี้ เช่น

ค่าติดตามทวงถาม
เดือน มกราคา 2555';


--
-- TOC entry 4089 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceAmt"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceAmt" IS 'จำนวนเงินที่ต้องชำระทั้งสิ้น';


--
-- TOC entry 4090 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceVATRate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceVATRate" IS '% อัตราภาษีมูลค่าเพิ่ม เฉพาะรายการนี้';


--
-- TOC entry 4091 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceAmtVAT"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceAmtVAT" IS 'จำนวน VAT เฉพาะรายการนี้';


--
-- TOC entry 4092 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceWHTRate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceWHTRate" IS '% อัตราภาษีหัก ณ ที่จ่าย';


--
-- TOC entry 4093 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceAmtWHT"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceAmtWHT" IS 'ยอดหัก ณ ที่จ่ายรายการนี้';


--
-- TOC entry 4094 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceIsSent"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceIsSent" IS 'ได้ส่ง Inv ไปให้ลูกค้าแล้วหรือยัง?
0 - ยัง
1 - ส่งไปแล้ว
2 - ตีคืน';


--
-- TOC entry 4095 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceAmtLeft"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceAmtLeft" IS '0.00 - จ่ายครบแล้ว
x.xx - จำนวนเงินที่ยังค้างจากยอด inv (จ่ายบางส่วนจึงเหลือ)';


--
-- TOC entry 4096 (class 0 OID 0)
-- Dependencies: 441
-- Name: COLUMN thcap_invoice."invoiceStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice."invoiceStatus" IS 'สถานะใบแจ้งหนี้
0 - DELETED / REJECTED
1 - ACTIVE
2 - USED TO ACTIVE BUT NOT ANYMORE (ถูกแทนที่ด้วยใบใหม่)
3 - CHECK FOR SUSPICIOUS (ตรวจสอบเนื่องจากพบข้อน่าสงสัย)
9 - WAIT FOR APPROVE';


--
-- TOC entry 4097 (class 0 OID 0)
-- Dependencies: 441
-- Name: CONSTRAINT "thcap_invoice_check_invAmtLeft_notAbvIniAmt" ON thcap_invoice; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_invoice_check_invAmtLeft_notAbvIniAmt" ON thcap_invoice IS 'หนี้คงเหลือของใบแจ้งหนี้จะต้องไปมากกว่ายอดหนี้ที่แจ้ง';


--
-- TOC entry 4098 (class 0 OID 0)
-- Dependencies: 441
-- Name: CONSTRAINT "thcap_invoice_check_invAmtLeft_notNeg" ON thcap_invoice; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_invoice_check_invAmtLeft_notNeg" ON thcap_invoice IS 'ยอดคงเหลือใบแจ้งหนี้จะไม่มีทางน้อยกว่า 0.00 หรือเป็นค่าติดลบ เป็นได้ต่ำสุดคือ 0.00 (จ่ายหมด)
สูงสุดคือยอดที่ต้องจ่าย (ยังไม่จ่าย)';


--
-- TOC entry 4099 (class 0 OID 0)
-- Dependencies: 441
-- Name: CONSTRAINT thcap_invoice_check_mg_wht ON thcap_invoice; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT thcap_invoice_check_mg_wht ON thcap_invoice IS 'ตรวจสอบว่าสัญญา ''MG'' จะต้องไม่มี WHT โดย WHTRate เป็น NULL และ AmtWHT = 0 เนื่องจาก MG เป็นสัญญาของบุคคลธรรมดา (บุคคลธรรมดาไม่มี WHT)';


--
-- TOC entry 445 (class 1259 OID 69351)
-- Dependencies: 6
-- Name: thcap_mg_invoice_payterm; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_mg_invoice_payterm (
    "invoiceID" character varying(30) NOT NULL,
    "ptNum" integer NOT NULL
);


ALTER TABLE account.thcap_mg_invoice_payterm OWNER TO dev;

--
-- TOC entry 4100 (class 0 OID 0)
-- Dependencies: 445
-- Name: TABLE thcap_mg_invoice_payterm; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_mg_invoice_payterm IS 'เก็บว่า Invoice ใบนั้นๆเกิดขึ้นในช่วงงวดไหน';


--
-- TOC entry 4101 (class 0 OID 0)
-- Dependencies: 445
-- Name: COLUMN thcap_mg_invoice_payterm."invoiceID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_invoice_payterm."invoiceID" IS 'เลขที่ใบแจ้งหนี้';


--
-- TOC entry 4102 (class 0 OID 0)
-- Dependencies: 445
-- Name: COLUMN thcap_mg_invoice_payterm."ptNum"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_invoice_payterm."ptNum" IS 'งวดที่จะจ่าย';


--
-- TOC entry 387 (class 1259 OID 66043)
-- Dependencies: 6
-- Name: thcap_mg_payTerm; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "thcap_mg_payTerm" (
    "contractID" character varying(25) NOT NULL,
    "ptNum" smallint NOT NULL,
    "ptDate" date NOT NULL,
    "ptMinPay" numeric(15,2) NOT NULL,
    "ptInvID" character varying(30)
);


ALTER TABLE account."thcap_mg_payTerm" OWNER TO dev;

--
-- TOC entry 4103 (class 0 OID 0)
-- Dependencies: 387
-- Name: TABLE "thcap_mg_payTerm"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE "thcap_mg_payTerm" IS 'ตารางการจ่ายเงินของลูกค้าแต่ละสัญญา';


--
-- TOC entry 4104 (class 0 OID 0)
-- Dependencies: 387
-- Name: COLUMN "thcap_mg_payTerm"."contractID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_mg_payTerm"."contractID" IS 'เลขที่สัญญา';


--
-- TOC entry 4105 (class 0 OID 0)
-- Dependencies: 387
-- Name: COLUMN "thcap_mg_payTerm"."ptNum"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_mg_payTerm"."ptNum" IS 'กำหนดครั้งที่';


--
-- TOC entry 4106 (class 0 OID 0)
-- Dependencies: 387
-- Name: COLUMN "thcap_mg_payTerm"."ptDate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_mg_payTerm"."ptDate" IS 'วันที่กำหนดชำระ';


--
-- TOC entry 4107 (class 0 OID 0)
-- Dependencies: 387
-- Name: COLUMN "thcap_mg_payTerm"."ptMinPay"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_mg_payTerm"."ptMinPay" IS 'ขั้นต่ำที่จะต้องจ่ายในกำหนดชำระครั้งนี้';


--
-- TOC entry 4108 (class 0 OID 0)
-- Dependencies: 387
-- Name: COLUMN "thcap_mg_payTerm"."ptInvID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_mg_payTerm"."ptInvID" IS 'Main Invoice หลักของการผ่อนจ่ายงวดนี้';


--
-- TOC entry 444 (class 1259 OID 69296)
-- Dependencies: 6
-- Name: thcap_receipt_details; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receipt_details (
    "recDetailsID" bigint NOT NULL,
    "receiptID" character varying(30) NOT NULL,
    futureuse01 character varying(10),
    "rToInvoiceID" character varying(30) NOT NULL,
    "rAmt" numeric(15,2),
    "rDiscount" numeric(15,2),
    "rNetAmt" numeric(15,2),
    "rVATRate" numeric(6,2),
    "rAmtVAT" numeric(15,2),
    "rWHTRate" numeric(6,2),
    "rAmtWHT" numeric(15,2)
);


ALTER TABLE account.thcap_receipt_details OWNER TO dev;

--
-- TOC entry 4109 (class 0 OID 0)
-- Dependencies: 444
-- Name: TABLE thcap_receipt_details; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_receipt_details IS 'รายการต่างๆในใบเสร็จ

เชื่อมกับ thcap_receipt แบบ 1-M


todo:
-----------------------------------------
ทำ check:  rNetAmt = rAmt - rDiscount
ทำ check: rVATAmt และ rWHTAmt ตามอัตรา';


--
-- TOC entry 4110 (class 0 OID 0)
-- Dependencies: 444
-- Name: COLUMN thcap_receipt_details."receiptID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_details."receiptID" IS 'รหัสเลขที่ใบเสร็จ';


--
-- TOC entry 4111 (class 0 OID 0)
-- Dependencies: 444
-- Name: COLUMN thcap_receipt_details.futureuse01; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_details.futureuse01 IS 'เดิมคือ rType (ประเภทการจ่าย)';


--
-- TOC entry 4112 (class 0 OID 0)
-- Dependencies: 444
-- Name: COLUMN thcap_receipt_details."rToInvoiceID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_details."rToInvoiceID" IS 'เป็นการตัดรายการของ Inv ใด';


--
-- TOC entry 4113 (class 0 OID 0)
-- Dependencies: 444
-- Name: COLUMN thcap_receipt_details."rAmt"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_details."rAmt" IS 'จำนวนเงินที่จ่าย';


--
-- TOC entry 447 (class 1259 OID 69648)
-- Dependencies: 3116 6
-- Name: V_thcap_mg_ptReceipt; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "V_thcap_mg_ptReceipt" AS
    SELECT "thcap_mg_payTerm"."contractID", "thcap_mg_payTerm"."ptNum", "thcap_mg_payTerm"."ptDate", "thcap_mg_payTerm"."ptMinPay", thcap_receipt_details."receiptID", thcap_receipt."receiveDate", thcap_receipt_details."rAmt" AS "AmtToThisPTNum" FROM ((((thcap_receipt RIGHT JOIN thcap_receipt_details ON ((((thcap_receipt."receiptID")::text = (thcap_receipt_details."receiptID")::text) AND (thcap_receipt."receiptStatus" = 1)))) RIGHT JOIN thcap_invoice ON ((((thcap_receipt_details."rToInvoiceID")::text = (thcap_invoice."invoiceID")::text) AND ((thcap_invoice."invoiceTypePay")::text = ((SELECT "thcap_mg_getMinPayType"() AS "thcap_mg_getMinPayType"))::text)))) RIGHT JOIN thcap_mg_invoice_payterm ON (((thcap_invoice."invoiceID")::text = (thcap_mg_invoice_payterm."invoiceID")::text))) RIGHT JOIN "thcap_mg_payTerm" ON ((((thcap_mg_invoice_payterm."ptNum")::text = ("thcap_mg_payTerm"."ptNum")::text) AND ((thcap_receipt."contractID")::text = ("thcap_mg_payTerm"."contractID")::text)))) ORDER BY "thcap_mg_payTerm"."contractID", "thcap_mg_payTerm"."ptNum", thcap_receipt."receiveDate";


ALTER TABLE account."V_thcap_mg_ptReceipt" OWNER TO dev;

--
-- TOC entry 4114 (class 0 OID 0)
-- Dependencies: 447
-- Name: VIEW "V_thcap_mg_ptReceipt"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON VIEW "V_thcap_mg_ptReceipt" IS 'แสดงยอดค้างที่เกิดขึ้นในช่วงต่างๆ และใบเสร็จทั้งหมดที่จ่ายในช่วงนั้นๆ และมีสถานะปกติ (receiptStatus=1)';


--
-- TOC entry 3825 (class 2606 OID 66047)
-- Dependencies: 387 387 387
-- Name: thcap_mg_payTerm_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "thcap_mg_payTerm"
    ADD CONSTRAINT "thcap_mg_payTerm_pkey" PRIMARY KEY ("contractID", "ptNum");


--
-- TOC entry 448 (class 1259 OID 69653)
-- Dependencies: 3117 6
-- Name: V_thcap_mg_ptReceiptSum; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "V_thcap_mg_ptReceiptSum" AS
    SELECT "thcap_mg_payTerm"."contractID", "thcap_mg_payTerm"."ptNum", "thcap_mg_payTerm"."ptDate", "thcap_mg_payTerm"."ptMinPay", sum(thcap_receipt_details."rAmt") AS "sumAmtToThisPTNum" FROM ((((thcap_receipt RIGHT JOIN thcap_receipt_details ON ((((thcap_receipt."receiptID")::text = (thcap_receipt_details."receiptID")::text) AND (thcap_receipt."receiptStatus" = 1)))) RIGHT JOIN thcap_invoice ON ((((thcap_receipt_details."rToInvoiceID")::text = (thcap_invoice."invoiceID")::text) AND ((thcap_invoice."invoiceTypePay")::text = ((SELECT "thcap_mg_getMinPayType"() AS "thcap_mg_getMinPayType"))::text)))) RIGHT JOIN thcap_mg_invoice_payterm ON (((thcap_invoice."invoiceID")::text = (thcap_mg_invoice_payterm."invoiceID")::text))) RIGHT JOIN "thcap_mg_payTerm" ON ((((thcap_mg_invoice_payterm."ptNum")::text = ("thcap_mg_payTerm"."ptNum")::text) AND ((thcap_receipt."contractID")::text = ("thcap_mg_payTerm"."contractID")::text)))) GROUP BY "thcap_mg_payTerm"."contractID", "thcap_mg_payTerm"."ptNum" ORDER BY "thcap_mg_payTerm"."contractID", "thcap_mg_payTerm"."ptNum";


ALTER TABLE account."V_thcap_mg_ptReceiptSum" OWNER TO dev;

--
-- TOC entry 4115 (class 0 OID 0)
-- Dependencies: 448
-- Name: VIEW "V_thcap_mg_ptReceiptSum"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON VIEW "V_thcap_mg_ptReceiptSum" IS 'แสดงยอดค้างที่เกิดขึ้นในช่วงต่างๆ และใบเสร็จทั้งหมดที่จ่ายในช่วงนั้นๆ และมีสถานะปกติ (receiptStatus=1)';


--
-- TOC entry 449 (class 1259 OID 69658)
-- Dependencies: 3118 6
-- Name: V_thcap_mg_receiptIntOnly; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "V_thcap_mg_receiptIntOnly" AS
    SELECT thcap_receipt."receiptID", thcap_receipt."contractID", thcap_invoice."invoiceTypePay", thcap_receipt."receiveDate", thcap_receipt_details."rAmt" FROM (((thcap_receipt LEFT JOIN thcap_receipt_details ON (((thcap_receipt."receiptID")::text = (thcap_receipt_details."receiptID")::text))) LEFT JOIN thcap_invoice ON ((((thcap_receipt_details."rToInvoiceID")::text = (thcap_invoice."invoiceID")::text) AND ((thcap_invoice."invoiceTypePay")::text = ((SELECT "thcap_mg_getInterestType"() AS "thcap_mg_getInterestType"))::text)))) LEFT JOIN thcap_mg_receipt_interest ON (((thcap_mg_receipt_interest."receiptID")::text = (thcap_receipt."receiptID")::text))) WHERE (thcap_receipt."receiptStatus" = 1) ORDER BY thcap_receipt."contractID", thcap_receipt."receiveDate";


ALTER TABLE account."V_thcap_mg_receiptIntOnly" OWNER TO dev;

--
-- TOC entry 4116 (class 0 OID 0)
-- Dependencies: 449
-- Name: VIEW "V_thcap_mg_receiptIntOnly"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON VIEW "V_thcap_mg_receiptIntOnly" IS 'แสดงใบเสร็จที่จ่ายค่าดอกเบี้ย และใบเสร็จนั้นใช้งานอยู่ปกติ (receiptStatus=1)


- ต้องเขียนแก้ใหม่';


--
-- TOC entry 450 (class 1259 OID 69663)
-- Dependencies: 3119 6
-- Name: V_thcap_receiptDetails; Type: VIEW; Schema: account; Owner: dev
--

CREATE VIEW "V_thcap_receiptDetails" AS
    SELECT thcap_receipt."receiptID", thcap_receipt."contractID", thcap_receipt."receiveDate", thcap_receipt."receiveAmt", thcap_receipt_details."rToInvoiceID", thcap_invoice."invoiceTypePay", thcap_invoice."invoiceTypePayRef", thcap_invoice."invoiceDescription", thcap_receipt_details."rAmt", thcap_receipt_details."rDiscount", thcap_receipt_details."rNetAmt", thcap_receipt_details."rVATRate", thcap_receipt_details."rAmtVAT", thcap_receipt_details."rWHTRate", thcap_receipt_details."rAmtWHT" FROM ((thcap_receipt LEFT JOIN thcap_receipt_details ON (((thcap_receipt."receiptID")::text = (thcap_receipt_details."receiptID")::text))) LEFT JOIN thcap_invoice ON (((thcap_invoice."invoiceID")::text = (thcap_receipt_details."rToInvoiceID")::text))) WHERE (thcap_receipt."receiptStatus" = 1) ORDER BY thcap_receipt."receiptID", thcap_invoice."invoiceTypePay";


ALTER TABLE account."V_thcap_receiptDetails" OWNER TO dev;

--
-- TOC entry 4117 (class 0 OID 0)
-- Dependencies: 450
-- Name: VIEW "V_thcap_receiptDetails"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON VIEW "V_thcap_receiptDetails" IS 'แสดงรายละเอียดการจ่ายของใบเสร็จแต่ละใบ';


--
-- TOC entry 206 (class 1259 OID 64711)
-- Dependencies: 3189 3190 6
-- Name: job_voucher; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE job_voucher (
    job_id integer NOT NULL,
    st_date date,
    vcp_finish boolean DEFAULT false,
    end_date date,
    cancel boolean DEFAULT false
);


ALTER TABLE account.job_voucher OWNER TO dev;

--
-- TOC entry 207 (class 1259 OID 64716)
-- Dependencies: 206 6
-- Name: job_voucher_job_id_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE job_voucher_job_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account.job_voucher_job_id_seq OWNER TO dev;

--
-- TOC entry 4118 (class 0 OID 0)
-- Dependencies: 207
-- Name: job_voucher_job_id_seq; Type: SEQUENCE OWNED BY; Schema: account; Owner: dev
--

ALTER SEQUENCE job_voucher_job_id_seq OWNED BY job_voucher.job_id;


--
-- TOC entry 208 (class 1259 OID 64718)
-- Dependencies: 6
-- Name: nw_voucher_type; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE nw_voucher_type (
    vtid character varying(12) NOT NULL,
    voucher_type_name character varying(254),
    voucher_type_desc character varying(254),
    voucher_type_status boolean
);


ALTER TABLE account.nw_voucher_type OWNER TO dev;

--
-- TOC entry 4119 (class 0 OID 0)
-- Dependencies: 208
-- Name: TABLE nw_voucher_type; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE nw_voucher_type IS 'ตารางจัดการประเภทค่าใช้จ่าย';


--
-- TOC entry 4120 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN nw_voucher_type.vtid; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN nw_voucher_type.vtid IS 'รหัสรายการ';


--
-- TOC entry 4121 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN nw_voucher_type.voucher_type_name; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN nw_voucher_type.voucher_type_name IS 'ประเภทค่าใช้จ่าย';


--
-- TOC entry 4122 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN nw_voucher_type.voucher_type_desc; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN nw_voucher_type.voucher_type_desc IS 'คำอธิบาย';


--
-- TOC entry 4123 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN nw_voucher_type.voucher_type_status; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN nw_voucher_type.voucher_type_status IS 'สถานะใช้งาน
t=ใช้งาน
f=ระงับการใช้งาน';


--
-- TOC entry 388 (class 1259 OID 66384)
-- Dependencies: 6
-- Name: thcap_channel; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_channel (
    "channelID" character varying(20) NOT NULL,
    "channelName" character varying(60) NOT NULL,
    "channelDesc" character varying,
    "channelSort" smallint NOT NULL
);


ALTER TABLE account.thcap_channel OWNER TO dev;

--
-- TOC entry 4124 (class 0 OID 0)
-- Dependencies: 388
-- Name: TABLE thcap_channel; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_channel IS 'ช่องทางการรับชำระ';


--
-- TOC entry 4125 (class 0 OID 0)
-- Dependencies: 388
-- Name: COLUMN thcap_channel."channelID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_channel."channelID" IS 'ID ประเภทช่องทางการรับชำระ';


--
-- TOC entry 4126 (class 0 OID 0)
-- Dependencies: 388
-- Name: COLUMN thcap_channel."channelName"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_channel."channelName" IS 'ชื่อประเภทช่องทาง';


--
-- TOC entry 4127 (class 0 OID 0)
-- Dependencies: 388
-- Name: COLUMN thcap_channel."channelDesc"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_channel."channelDesc" IS 'คำอธิบาย';


--
-- TOC entry 4128 (class 0 OID 0)
-- Dependencies: 388
-- Name: COLUMN thcap_channel."channelSort"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_channel."channelSort" IS 'เรียงลำดับความสำคัญ';


--
-- TOC entry 418 (class 1259 OID 68487)
-- Dependencies: 3377 3378 6
-- Name: thcap_dncn; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_dncn (
    "dcNoteID" character varying(30) NOT NULL,
    "dcType" character varying(10) NOT NULL,
    "dcCompID" character varying(30) NOT NULL,
    "invoiceID" character varying(30) NOT NULL,
    "dcNoteDate" timestamp without time zone NOT NULL,
    "dcNoteDescription" character varying(200),
    "dcNoteAmt" numeric(15,2) NOT NULL,
    "dcNoteVATRate" numeric(6,2),
    "dcNoteAmtVAT" numeric(15,2) NOT NULL,
    "dcNoteFlow" smallint DEFAULT 0 NOT NULL,
    "dcNoteStatus" smallint DEFAULT 9 NOT NULL
);


ALTER TABLE account.thcap_dncn OWNER TO dev;

--
-- TOC entry 4129 (class 0 OID 0)
-- Dependencies: 418
-- Name: TABLE thcap_dncn; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_dncn IS 'Table เก็บข้อมูล Debit Note / Credit Note';


--
-- TOC entry 4130 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."dcNoteID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."dcNoteID" IS 'รหัส CreditNote หรือ DebitNote';


--
-- TOC entry 4131 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."dcType"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."dcType" IS '''CN'' = Credit Note
''DN'' = Debit Note';


--
-- TOC entry 4132 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."dcCompID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."dcCompID" IS 'รหัสบริษัทที่ออก DN/CN';


--
-- TOC entry 4133 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."invoiceID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."invoiceID" IS 'เลขที่ใบแจ้งหนี้';


--
-- TOC entry 4134 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."dcNoteDate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."dcNoteDate" IS 'วันที่รายการออกมีผล';


--
-- TOC entry 4135 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."dcNoteDescription"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."dcNoteDescription" IS 'Description ของรายการนี้ เช่น

ค่าติดตามทวงถาม
เดือน มกราคา 2555';


--
-- TOC entry 4136 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."dcNoteAmt"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."dcNoteAmt" IS 'จำนวนเงินที่ต้องชำระทั้งสิ้น';


--
-- TOC entry 4137 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."dcNoteVATRate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."dcNoteVATRate" IS '% อัตราภาษีมูลค่าเพิ่ม เฉพาะรายการนี้';


--
-- TOC entry 4138 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."dcNoteAmtVAT"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."dcNoteAmtVAT" IS 'จำนวน VAT เฉพาะรายการนี้';


--
-- TOC entry 4139 (class 0 OID 0)
-- Dependencies: 418
-- Name: COLUMN thcap_dncn."dcNoteStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn."dcNoteStatus" IS 'สถานะใบแจ้งหนี้
0 - NOT APPROVE
1 - ACTIVED
2 - CALCELLED
9 - WAIT FOR APPROVE';


--
-- TOC entry 416 (class 1259 OID 67984)
-- Dependencies: 3351 3352 3353 6
-- Name: thcap_dncn_action; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_dncn_action (
    "dcNoteActionID" bigint NOT NULL,
    "dcNoteActionType" character varying(10) NOT NULL,
    "dcNoteID" character varying(20) NOT NULL,
    "serialAction" smallint DEFAULT 1 NOT NULL,
    "doerID" character varying(12) NOT NULL,
    "doerStamp" timestamp without time zone NOT NULL,
    "doerRemask" character varying,
    "appvXID" character varying(12),
    "appvXStamp" timestamp without time zone,
    "appvXRemask" character varying,
    "appvXStatus" smallint,
    "appvYID" character varying(12),
    "appvYStamp" timestamp without time zone,
    "appvYRemask" character varying,
    "appvYStatus" smallint,
    "auditorXID" character varying(12),
    "auditorXStamp" timestamp without time zone,
    "auditorXRemask" character varying,
    "auditorXStatus" smallint,
    "auditorYID" character varying(12),
    "auditorYStamp" timestamp without time zone,
    "auditorYRemask" character varying,
    "auditorYStatus" smallint,
    CONSTRAINT thcap_dncn_action_appvx CHECK ((((("appvXID" IS NULL) AND ("appvXStamp" IS NULL)) AND ("appvXStatus" IS NULL)) OR ((("appvXID" IS NOT NULL) AND ("appvXStamp" IS NOT NULL)) AND ("appvXStatus" IS NOT NULL)))),
    CONSTRAINT thcap_dncn_action_appvy CHECK ((((("appvYID" IS NULL) AND ("appvXStamp" IS NULL)) AND ("appvXStatus" IS NULL)) OR ((("appvYID" IS NOT NULL) AND ("appvXStamp" IS NOT NULL)) AND ("appvXStatus" IS NOT NULL))))
);


ALTER TABLE account.thcap_dncn_action OWNER TO dev;

--
-- TOC entry 4140 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."dcNoteActionID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."dcNoteActionID" IS 'รหัส running รายการ';


--
-- TOC entry 4141 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."dcNoteActionType"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."dcNoteActionType" IS 'I - Input
C - Cancel';


--
-- TOC entry 4142 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."dcNoteID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."dcNoteID" IS 'รหัสรายการ Debit/Credit Note ที่ link เชื่อมกัน';


--
-- TOC entry 4143 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."serialAction"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."serialAction" IS 'ครั้งที่ของรายการตามประเภท I / C';


--
-- TOC entry 4144 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."doerID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."doerID" IS 'ผู้ทำรายการ';


--
-- TOC entry 4145 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."doerStamp"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."doerStamp" IS 'วันเวลาที่ทำรายการ';


--
-- TOC entry 4146 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."doerRemask"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."doerRemask" IS 'เหตุผลของผู้ทำรายการ (ถ้ามี)';


--
-- TOC entry 4147 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."appvXStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."appvXStatus" IS '0-ไม่อนุมัติ
1-อนุมัติ';


--
-- TOC entry 4148 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."appvYStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."appvYStatus" IS '0-ไม่อนุมัติ
1-อนุมัติ';


--
-- TOC entry 4149 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."auditorXStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."auditorXStatus" IS '0-ไม่ถูกต้อง
1-ถูกต้อง';


--
-- TOC entry 4150 (class 0 OID 0)
-- Dependencies: 416
-- Name: COLUMN thcap_dncn_action."auditorYStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_dncn_action."auditorYStatus" IS '0-ไม่ถูกต้อง
1-ถูกต้อง';


--
-- TOC entry 415 (class 1259 OID 67982)
-- Dependencies: 416 6
-- Name: thcap_dncn_action_dcNoteActionID_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "thcap_dncn_action_dcNoteActionID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."thcap_dncn_action_dcNoteActionID_seq" OWNER TO dev;

--
-- TOC entry 4151 (class 0 OID 0)
-- Dependencies: 415
-- Name: thcap_dncn_action_dcNoteActionID_seq; Type: SEQUENCE OWNED BY; Schema: account; Owner: dev
--

ALTER SEQUENCE "thcap_dncn_action_dcNoteActionID_seq" OWNED BY thcap_dncn_action."dcNoteActionID";


--
-- TOC entry 408 (class 1259 OID 67388)
-- Dependencies: 3346 3347 3348 6
-- Name: thcap_invoice_action; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_invoice_action (
    "invActionID" bigint NOT NULL,
    "invActionType" character varying(10) NOT NULL,
    "invoiceID" character varying(20) NOT NULL,
    "serialAction" smallint DEFAULT 1 NOT NULL,
    "doerID" character varying(12) NOT NULL,
    "doerStamp" timestamp without time zone NOT NULL,
    "doerRemask" character varying,
    "appvXID" character varying(12),
    "appvXStamp" timestamp without time zone,
    "appvXRemask" character varying,
    "appvXStatus" smallint,
    "appvYID" character varying(12),
    "appvYStamp" timestamp without time zone,
    "appvYRemask" character varying,
    "appvYStatus" smallint,
    "auditorXID" character varying(12),
    "auditorXStamp" timestamp without time zone,
    "auditorXRemask" character varying,
    "auditorXStatus" smallint,
    "auditorYID" character varying(12),
    "auditorYStamp" timestamp without time zone,
    "auditorYRemask" character varying,
    "auditorYStatus" smallint,
    CONSTRAINT thcap_invoice_action_appvx CHECK ((((("appvXID" IS NULL) AND ("appvXStamp" IS NULL)) AND ("appvXStatus" IS NULL)) OR ((("appvXID" IS NOT NULL) AND ("appvXStamp" IS NOT NULL)) AND ("appvXStatus" IS NOT NULL)))),
    CONSTRAINT thcap_invoice_action_appvy CHECK ((((("appvYID" IS NULL) AND ("appvYStamp" IS NULL)) AND ("appvYStatus" IS NULL)) OR ((("appvYID" IS NOT NULL) AND ("appvYStamp" IS NOT NULL)) AND ("appvYStatus" IS NOT NULL))))
);


ALTER TABLE account.thcap_invoice_action OWNER TO dev;

--
-- TOC entry 4152 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."invActionID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."invActionID" IS 'รหัส running รายการ';


--
-- TOC entry 4153 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."invActionType"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."invActionType" IS 'I - Input
C - Cancel';


--
-- TOC entry 4154 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."invoiceID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."invoiceID" IS 'รหัสรายการใบแจ้งหนี้ที่ link เชื่อมกัน';


--
-- TOC entry 4155 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."serialAction"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."serialAction" IS 'ครั้งที่ของรายการตามประเภท I / C';


--
-- TOC entry 4156 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."doerID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."doerID" IS 'ผู้ทำการออกใบแจ้งหนี้';


--
-- TOC entry 4157 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."doerStamp"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."doerStamp" IS 'วันเวลาที่ออกใบแจ้งหนี้';


--
-- TOC entry 4158 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."doerRemask"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."doerRemask" IS 'เหตุผลของผู้ออกใบแจ้งหนี้ (ถ้ามี)';


--
-- TOC entry 4159 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."appvXStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."appvXStatus" IS '0-ไม่อนุมัติ
1-อนุมัติ';


--
-- TOC entry 4160 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."appvYStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."appvYStatus" IS '0-ไม่อนุมัติ
1-อนุมัติ';


--
-- TOC entry 4161 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."auditorXStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."auditorXStatus" IS '0-ไม่ถูกต้อง
1-ถูกต้อง';


--
-- TOC entry 4162 (class 0 OID 0)
-- Dependencies: 408
-- Name: COLUMN thcap_invoice_action."auditorYStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_invoice_action."auditorYStatus" IS '0-ไม่ถูกต้อง
1-ถูกต้อง';


--
-- TOC entry 407 (class 1259 OID 67386)
-- Dependencies: 6 408
-- Name: thcap_invoice_action_invActionID_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "thcap_invoice_action_invActionID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."thcap_invoice_action_invActionID_seq" OWNER TO dev;

--
-- TOC entry 4163 (class 0 OID 0)
-- Dependencies: 407
-- Name: thcap_invoice_action_invActionID_seq; Type: SEQUENCE OWNED BY; Schema: account; Owner: dev
--

ALTER SEQUENCE "thcap_invoice_action_invActionID_seq" OWNED BY thcap_invoice_action."invActionID";


--
-- TOC entry 442 (class 1259 OID 69258)
-- Dependencies: 6
-- Name: thcap_mg_receipt_principle; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_mg_receipt_principle (
    "receiptID" character varying(30) NOT NULL,
    "amtReturnPriciple" numeric(15,2)
);


ALTER TABLE account.thcap_mg_receipt_principle OWNER TO dev;

--
-- TOC entry 4164 (class 0 OID 0)
-- Dependencies: 442
-- Name: COLUMN thcap_mg_receipt_principle."receiptID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_receipt_principle."receiptID" IS 'เลขที่ใบเสร็จ';


--
-- TOC entry 4165 (class 0 OID 0)
-- Dependencies: 442
-- Name: COLUMN thcap_mg_receipt_principle."amtReturnPriciple"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_receipt_principle."amtReturnPriciple" IS 'จำนวนเงินที่จ่ายคืนเงินต้น';


--
-- TOC entry 209 (class 1259 OID 64724)
-- Dependencies: 3192 6
-- Name: thcap_mg_statement; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_mg_statement (
    "contractID" character varying(35) NOT NULL,
    "statementSerial" integer NOT NULL,
    "receiptID" character varying(30),
    "CNID" character varying(30),
    "DNID" character varying(30),
    futureuse01 character varying(30),
    futureuse02 integer,
    "sBeforePrinciple" numeric(15,2),
    "sBeforeInterest" numeric(15,2),
    "sAfterPrinciple" numeric(15,2),
    "sAfterInterest" numeric(15,2),
    CONSTRAINT "oneSourcePerStatementChanged" CHECK (((((("receiptID" IS NOT NULL) AND ("CNID" IS NULL)) AND ("DNID" IS NULL)) OR ((("receiptID" IS NULL) AND ("CNID" IS NOT NULL)) AND ("DNID" IS NULL))) OR ((("receiptID" IS NULL) AND ("CNID" IS NULL)) AND ("DNID" IS NOT NULL))))
);


ALTER TABLE account.thcap_mg_statement OWNER TO dev;

--
-- TOC entry 4166 (class 0 OID 0)
-- Dependencies: 209
-- Name: TABLE thcap_mg_statement; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_mg_statement IS 'ข้อมูล Statement ของสัญญาจำนอง';


--
-- TOC entry 4167 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN thcap_mg_statement."contractID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_statement."contractID" IS 'เลขที่สัญญาหลัก';


--
-- TOC entry 4168 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN thcap_mg_statement."receiptID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_statement."receiptID" IS 'เลขที่ใบเสร็จรับเงิน';


--
-- TOC entry 4169 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN thcap_mg_statement."CNID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_statement."CNID" IS 'Credit Note ID';


--
-- TOC entry 4170 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN thcap_mg_statement."DNID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_statement."DNID" IS 'Debit Note ID';


--
-- TOC entry 4171 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN thcap_mg_statement."sBeforePrinciple"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_statement."sBeforePrinciple" IS 'เงินต้นก่อนหักยอดในใบเสร็จ';


--
-- TOC entry 4172 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN thcap_mg_statement."sBeforeInterest"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_statement."sBeforeInterest" IS 'ดอกเบี้ยก่อนหักยอดในใบเสร็จ';


--
-- TOC entry 4173 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN thcap_mg_statement."sAfterPrinciple"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_statement."sAfterPrinciple" IS 'เงินต้นคงเหลือหลังหักยอดในใบเสร็จ';


--
-- TOC entry 4174 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN thcap_mg_statement."sAfterInterest"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_mg_statement."sAfterInterest" IS 'ดอกเบี้ยคงเหลือหลังหักยอดในใบเสร็จ';


--
-- TOC entry 4175 (class 0 OID 0)
-- Dependencies: 209
-- Name: CONSTRAINT "oneSourcePerStatementChanged" ON thcap_mg_statement; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "oneSourcePerStatementChanged" ON thcap_mg_statement IS 'Statement ที่เกิดขึ้นจะต้องเกิดจาก Receipt / Debit Note / Credit Note อย่างใดอย่างหนึ่งเท่านั้น จะไม่มีเลย หรือมีมากกว่า 1 อย่างไม่ได้';


--
-- TOC entry 406 (class 1259 OID 67344)
-- Dependencies: 3344 6
-- Name: thcap_receipt_action; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receipt_action (
    "recActionID" bigint NOT NULL,
    "recActionType" character varying(10) NOT NULL,
    "receiptID" character varying(20) NOT NULL,
    "serialAction" smallint DEFAULT 1 NOT NULL,
    "doerID" character varying(12) NOT NULL,
    "doerStamp" timestamp without time zone NOT NULL,
    "doerRemask" character varying,
    "appvXID" character varying(12),
    "appvXStamp" timestamp without time zone,
    "appvXRemask" character varying,
    "appvXStatus" smallint,
    "appvYID" character varying(12),
    "appvYStamp" timestamp without time zone,
    "appvYRemask" character varying,
    "appvYStatus" smallint,
    "auditorXID" character varying(12),
    "auditorXStamp" timestamp without time zone,
    "auditorXRemask" character varying,
    "auditorXStatus" smallint,
    "auditorYID" character varying(12),
    "auditorYStamp" timestamp without time zone,
    "auditorYRemask" character varying,
    "auditorYStatus" smallint
);


ALTER TABLE account.thcap_receipt_action OWNER TO dev;

--
-- TOC entry 4176 (class 0 OID 0)
-- Dependencies: 406
-- Name: TABLE thcap_receipt_action; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_receipt_action IS 'ข้อมูลการเปลี่ยนแปลงที่เกิดขึ้นกับใบเสร็จ

เชื่อมกับ thcap_receipt แบบ 1-M';


--
-- TOC entry 4177 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."recActionID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."recActionID" IS 'รหัส running รายการ';


--
-- TOC entry 4178 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."recActionType"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."recActionType" IS 'I - Input
C - Cancel';


--
-- TOC entry 4179 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."receiptID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."receiptID" IS 'รหัสรายการใบเสร็จที่ link เชื่อมกัน';


--
-- TOC entry 4180 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."serialAction"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."serialAction" IS 'ครั้งที่ของรายการตามประเภท I / C';


--
-- TOC entry 4181 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."doerID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."doerID" IS 'ผู้ทำการรับชำระและออกใบเสร็จ';


--
-- TOC entry 4182 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."doerStamp"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."doerStamp" IS 'วันเวลาที่กดรับชำระและออกใบเสร็จ';


--
-- TOC entry 4183 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."doerRemask"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."doerRemask" IS 'เหตุผลของผู้รับชำระ (ถ้ามี)';


--
-- TOC entry 4184 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."appvXStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."appvXStatus" IS '0-ไม่อนุมัติ
1-อนุมัติ';


--
-- TOC entry 4185 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."appvYStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."appvYStatus" IS '0-ไม่อนุมัติ
1-อนุมัติ';


--
-- TOC entry 4186 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."auditorXStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."auditorXStatus" IS '0-ไม่ถูกต้อง
1-ถูกต้อง';


--
-- TOC entry 4187 (class 0 OID 0)
-- Dependencies: 406
-- Name: COLUMN thcap_receipt_action."auditorYStatus"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_action."auditorYStatus" IS '0-ไม่ถูกต้อง
1-ถูกต้อง';


--
-- TOC entry 405 (class 1259 OID 67342)
-- Dependencies: 406 6
-- Name: thcap_receipt_action_recActionID_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "thcap_receipt_action_recActionID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."thcap_receipt_action_recActionID_seq" OWNER TO dev;

--
-- TOC entry 4188 (class 0 OID 0)
-- Dependencies: 405
-- Name: thcap_receipt_action_recActionID_seq; Type: SEQUENCE OWNED BY; Schema: account; Owner: dev
--

ALTER SEQUENCE "thcap_receipt_action_recActionID_seq" OWNED BY thcap_receipt_action."recActionID";


--
-- TOC entry 411 (class 1259 OID 67736)
-- Dependencies: 6
-- Name: thcap_receipt_channel; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receipt_channel (
    "recChannelID" bigint NOT NULL,
    "receiptID" character varying(30) NOT NULL,
    "recChannelType" character varying(30) NOT NULL,
    "recChannelRef" character varying(30) NOT NULL,
    "recChannelAmt" numeric(15,2) NOT NULL
);


ALTER TABLE account.thcap_receipt_channel OWNER TO dev;

--
-- TOC entry 4189 (class 0 OID 0)
-- Dependencies: 411
-- Name: TABLE thcap_receipt_channel; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_receipt_channel IS 'ข้อมูลแสดงรายละเอียดที่มาของเงินในใบเสร็จ

เชื่อมกับ account.thcap_receipt แบบ 1-M';


--
-- TOC entry 4190 (class 0 OID 0)
-- Dependencies: 411
-- Name: COLUMN thcap_receipt_channel."receiptID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_channel."receiptID" IS 'เลขที่ใบเสร็จรับเงิน';


--
-- TOC entry 4191 (class 0 OID 0)
-- Dependencies: 411
-- Name: COLUMN thcap_receipt_channel."recChannelType"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_channel."recChannelType" IS 'ประเภทช่องทาง';


--
-- TOC entry 4192 (class 0 OID 0)
-- Dependencies: 411
-- Name: COLUMN thcap_receipt_channel."recChannelRef"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_channel."recChannelRef" IS 'รหัสของช่องทางนั้นๆ';


--
-- TOC entry 4193 (class 0 OID 0)
-- Dependencies: 411
-- Name: COLUMN thcap_receipt_channel."recChannelAmt"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_channel."recChannelAmt" IS 'จำนวนเงินสุทธิที่รับจากช่องทางนั้นๆ จะต้องเท่ากับ netAmt';


--
-- TOC entry 410 (class 1259 OID 67734)
-- Dependencies: 6 411
-- Name: thcap_receipt_channel_recChannelID_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "thcap_receipt_channel_recChannelID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."thcap_receipt_channel_recChannelID_seq" OWNER TO dev;

--
-- TOC entry 4194 (class 0 OID 0)
-- Dependencies: 410
-- Name: thcap_receipt_channel_recChannelID_seq; Type: SEQUENCE OWNED BY; Schema: account; Owner: dev
--

ALTER SEQUENCE "thcap_receipt_channel_recChannelID_seq" OWNED BY thcap_receipt_channel."recChannelID";


--
-- TOC entry 409 (class 1259 OID 67493)
-- Dependencies: 6
-- Name: thcap_receipt_desc; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receipt_desc (
    "receiptID" character varying(30) NOT NULL,
    "cusFullName" character varying(200),
    "doerFullName" character varying(200),
    "receiveRate" numeric(6,2)
);


ALTER TABLE account.thcap_receipt_desc OWNER TO dev;

--
-- TOC entry 4195 (class 0 OID 0)
-- Dependencies: 409
-- Name: TABLE thcap_receipt_desc; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_receipt_desc IS 'ข้อมูลใบเสร็จที่รับชำระคืนเงินกู้จำนอง

เชื่อมกับ account.thcap_receipt แบบ 1-1';


--
-- TOC entry 4196 (class 0 OID 0)
-- Dependencies: 409
-- Name: COLUMN thcap_receipt_desc."receiptID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_desc."receiptID" IS 'เลขที่ใบเสร็จรับเงิน';


--
-- TOC entry 4197 (class 0 OID 0)
-- Dependencies: 409
-- Name: COLUMN thcap_receipt_desc."cusFullName"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_desc."cusFullName" IS 'คำนำหน้า-ชื่อ-นามสกุล ที่ปรากฎบนใบเสร็จ';


--
-- TOC entry 4198 (class 0 OID 0)
-- Dependencies: 409
-- Name: COLUMN thcap_receipt_desc."doerFullName"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_desc."doerFullName" IS 'ชื่อ-นามสกุล ผู้ออกใบเสร็จ';


--
-- TOC entry 4199 (class 0 OID 0)
-- Dependencies: 409
-- Name: COLUMN thcap_receipt_desc."receiveRate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN thcap_receipt_desc."receiveRate" IS 'อัตราดอกเบี้ยที่ปรากฎบนใบเสร็จ';


--
-- TOC entry 443 (class 1259 OID 69294)
-- Dependencies: 6 444
-- Name: thcap_receipt_details_recDetailsID_seq; Type: SEQUENCE; Schema: account; Owner: dev
--

CREATE SEQUENCE "thcap_receipt_details_recDetailsID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account."thcap_receipt_details_recDetailsID_seq" OWNER TO dev;

--
-- TOC entry 4200 (class 0 OID 0)
-- Dependencies: 443
-- Name: thcap_receipt_details_recDetailsID_seq; Type: SEQUENCE OWNED BY; Schema: account; Owner: dev
--

ALTER SEQUENCE "thcap_receipt_details_recDetailsID_seq" OWNED BY thcap_receipt_details."recDetailsID";


--
-- TOC entry 453 (class 1259 OID 69707)
-- Dependencies: 3447 3448 6
-- Name: thcap_typePay; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "thcap_typePay" (
    "tpID" character varying(10) NOT NULL,
    "tpCompanyID" character varying(10) NOT NULL,
    "tpConType" character varying(30) NOT NULL,
    "tpDesc" character varying(100) NOT NULL,
    "tpFullDesc" character varying,
    "tpType" character varying(20) NOT NULL,
    "tpRanking" integer NOT NULL,
    "ableB" smallint NOT NULL,
    "ableDiscount" smallint NOT NULL,
    "ableWaive" smallint NOT NULL,
    "ableVAT" smallint NOT NULL,
    "ableWHT" smallint NOT NULL,
    "ableSkip" smallint NOT NULL,
    "ablePartial" smallint NOT NULL,
    "curWHTRate" numeric(6,2),
    "isServices" smallint,
    "whoSeen" character varying(200) DEFAULT 'ALL'::character varying NOT NULL,
    "tpRefType" character varying(30),
    "tpSort" integer,
    CONSTRAINT "checkWHTInfo" CHECK (((("ableWHT" = 1) AND ("curWHTRate" IS NOT NULL)) OR (("ableWHT" = 0) AND ("curWHTRate" IS NULL))))
);


ALTER TABLE account."thcap_typePay" OWNER TO dev;

--
-- TOC entry 4201 (class 0 OID 0)
-- Dependencies: 453
-- Name: TABLE "thcap_typePay"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE "thcap_typePay" IS 'VAT ที่ใช้กรณีมีการเปลี่ยนแปลงให้ใช้ตามไหน
1 - ตามข้อมูล typePay นี้
2 - ตาม INVOICE ที่เคยออกไปขณะนั้นๆแล้ว';


--
-- TOC entry 4202 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."tpID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."tpID" IS 'รหัสค่าใช้จ่าย';


--
-- TOC entry 4203 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."tpCompanyID"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."tpCompanyID" IS 'รหัสบริษัท';


--
-- TOC entry 4204 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."tpConType"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."tpConType" IS 'รหัสประเภทสัญญาที่ค่าใช้จ่ายใช้เรียกเก็บ';


--
-- TOC entry 4205 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."tpDesc"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."tpDesc" IS 'รายละเอียด';


--
-- TOC entry 4206 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."tpFullDesc"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."tpFullDesc" IS 'รายละเอียดแบบยาว';


--
-- TOC entry 4207 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."tpType"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."tpType" IS 'NONE - ไม่มีเงื่อนไขในการเก็บ
LOCKED - เหมือนกับ NONE แต่ว่าไม่ให้เพิ่มหนี้เข้าไปได้โดยทั่วไป
FIXED - เก็บค่าตายตัวทุกสัญญาเหมือนกันหมด
VAR - เก็บค่าไม่เหมือนกันแปรผันตามสัญญา
PER - เก็บค่าเป็น percent จากยอดที่สนใจ';


--
-- TOC entry 4208 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."tpRanking"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."tpRanking" IS 'อันดับการจ่าย
0-ไม่มีลำดับการจ่าย
1-ต้องจ่ายก่อน
max integer-จ่ายหลังสุด

ลำดับเดียวกันหมายถึงสำคัญเท่ากัน จ่ายอะไรก่อนก็ได้';


--
-- TOC entry 4209 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."ableB"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."ableB" IS 'Book?';


--
-- TOC entry 4210 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."ableDiscount"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."ableDiscount" IS 'type นี้สามารถลดได้';


--
-- TOC entry 4211 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."ableWaive"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."ableWaive" IS 'type นี้สามารถ Waive ไม่เก็บได้';


--
-- TOC entry 4212 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."ableVAT"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."ableVAT" IS 'type นี้มี VAT ด้วย';


--
-- TOC entry 4213 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."ableWHT"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."ableWHT" IS 'type นี้มี WHT ด้วย (หักกับเฉพาะนิติบุคคล)';


--
-- TOC entry 4214 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."ableSkip"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."ableSkip" IS 'สามารถข้ามการเก็บเงินได้ / เช่นไว้จ่ายทีหลัง';


--
-- TOC entry 4215 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."ablePartial"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."ablePartial" IS 'จ่ายบางส่วนได้หรือไม่';


--
-- TOC entry 4216 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."curWHTRate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."curWHTRate" IS '% อัตราภาษีหัก ณ ที่จ่ายปัจจุบันของค่าใช้จ่ายประเภทนี้';


--
-- TOC entry 4217 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."isServices"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."isServices" IS 'เป็นสินค้าหรือค่าบริการ / มีผลต่อเรื่อง VAT (WHT ?)
0 - ไม่เข้าข่ายทั้ง ค่าสินค้า หรือบริการ
1 - บริการ ยึดการคิด VAT ณ วันจ่าย (เพราะเป็นค่าบริการ VAT ส่งเมื่อจ่าย)
2 - สินค้า ยึดการคิด VAT ตาม invoice (เพราะเป็นสินค้า VAT ส่งหลวงแล้ว)';


--
-- TOC entry 4218 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."whoSeen"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."whoSeen" IS 'ALL - เปิดให้เห็นทุกส่วนงาน';


--
-- TOC entry 4219 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."tpRefType"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."tpRefType" IS 'รูปแบบ Ref
D - วันที่ (20121231)
W - สัปดาห์ (201201W1)
M - monthly รายเดือน (201201)
Y - yearly รายปี (2012)
L - ช่วงใดๆ (20121201-20121231)
RUNNING - ครั้งที่ (เริ่มที่ 1)
ID - ตามหนังสือหรือรหัสใบ
DUE - Due หรือ งวดที่กำหนด เช่น งวด 1 (1) หรือจ่ายงวด 1 มากกว่าปกติ (1_1, 1_2, 1_3, ..., 1_11, ...)';


--
-- TOC entry 4220 (class 0 OID 0)
-- Dependencies: 453
-- Name: COLUMN "thcap_typePay"."tpSort"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay"."tpSort" IS 'เรียงลำดับการแสดงผล น้อยสุดอยู่บนสุด';


--
-- TOC entry 438 (class 1259 OID 69079)
-- Dependencies: 6
-- Name: thcap_typePay_fixed; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE "thcap_typePay_fixed" (
    "tpID" character varying(10) NOT NULL,
    "tpEffDate" date NOT NULL,
    "tpEndDate" date,
    "tpAmtFixed" numeric(15,2) NOT NULL,
    "tpDescFixed" character varying
);


ALTER TABLE account."thcap_typePay_fixed" OWNER TO dev;

--
-- TOC entry 4221 (class 0 OID 0)
-- Dependencies: 438
-- Name: COLUMN "thcap_typePay_fixed"."tpEffDate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay_fixed"."tpEffDate" IS 'วันที่มีผล';


--
-- TOC entry 4222 (class 0 OID 0)
-- Dependencies: 438
-- Name: COLUMN "thcap_typePay_fixed"."tpEndDate"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay_fixed"."tpEndDate" IS 'วันที่สิ้นสุดการมีผล (ไม่นับ)';


--
-- TOC entry 4223 (class 0 OID 0)
-- Dependencies: 438
-- Name: COLUMN "thcap_typePay_fixed"."tpAmtFixed"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN "thcap_typePay_fixed"."tpAmtFixed" IS 'จำนวนเงินที่ FIXED';


--
-- TOC entry 412 (class 1259 OID 67766)
-- Dependencies: 6
-- Name: thcap_wht; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_wht (
);


ALTER TABLE account.thcap_wht OWNER TO dev;

--
-- TOC entry 4224 (class 0 OID 0)
-- Dependencies: 412
-- Name: TABLE thcap_wht; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_wht IS 'Table คุมเรื่องภาษีหัก ณ ที่จ่าย';


--
-- TOC entry 413 (class 1259 OID 67769)
-- Dependencies: 6
-- Name: thcap_wht_desc; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_wht_desc (
);


ALTER TABLE account.thcap_wht_desc OWNER TO dev;

--
-- TOC entry 4225 (class 0 OID 0)
-- Dependencies: 413
-- Name: TABLE thcap_wht_desc; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_wht_desc IS 'รายละเอียดบนใบหักภาษี ณ ที่จ่าย';


--
-- TOC entry 414 (class 1259 OID 67772)
-- Dependencies: 6
-- Name: thcap_wht_details; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_wht_details (
);


ALTER TABLE account.thcap_wht_details OWNER TO dev;

--
-- TOC entry 4226 (class 0 OID 0)
-- Dependencies: 414
-- Name: TABLE thcap_wht_details; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE thcap_wht_details IS 'รายการในใบหักภาษี ณ ที่จ่าย';


--
-- TOC entry 210 (class 1259 OID 64727)
-- Dependencies: 6
-- Name: vender; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE vender (
    "VenderID" character varying(10) NOT NULL,
    type_vd character varying(20),
    vd_name character varying(100),
    vd_address text,
    vd_tel character varying(50),
    acid character varying(5)
);


ALTER TABLE account.vender OWNER TO dev;

--
-- TOC entry 4227 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN vender.type_vd; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN vender.type_vd IS 'บจก. บริษัท จำกัด
บมจ. บริษัท มหาชน จำกัด
หจก. ห้างหุ้นส่วนจำกัด
นาย . นาง. นางสาว';


--
-- TOC entry 211 (class 1259 OID 64733)
-- Dependencies: 3193 6
-- Name: voucher; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE voucher (
    vc_id character varying(15) NOT NULL,
    vc_detail text,
    marker_id character varying(10),
    approve_id character varying(10),
    receipt_id character varying(10),
    cash_amt double precision,
    chq_acc_no character varying(20),
    chque_no character varying(10),
    do_date date,
    job_id bigint,
    vc_type character(1) DEFAULT 'P'::bpchar,
    autoid_abh bigint,
    appv_date date,
    recp_date date
);


ALTER TABLE account.voucher OWNER TO dev;

--
-- TOC entry 4228 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN voucher.vc_type; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON COLUMN voucher.vc_type IS 'type of voucher  ''P'' is pay , ''R'' is receipt';


--
-- TOC entry 212 (class 1259 OID 64740)
-- Dependencies: 6
-- Name: voucher_details; Type: TABLE; Schema: account; Owner: dev; Tablespace: 
--

CREATE TABLE voucher_details (
    vc_id character varying(15) NOT NULL,
    vtid character varying(12)
);


ALTER TABLE account.voucher_details OWNER TO dev;

--
-- TOC entry 4229 (class 0 OID 0)
-- Dependencies: 212
-- Name: TABLE voucher_details; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TABLE voucher_details IS 'ตารางเก็บผู้เบิก/ผู้ส่งมอบเงินและรายการค่าใช้จ่าย';


SET search_path = carregis, pg_catalog;

--
-- TOC entry 213 (class 1259 OID 64743)
-- Dependencies: 3194 7
-- Name: CarID; Type: TABLE; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE TABLE "CarID" (
    monthid date NOT NULL,
    carid smallint DEFAULT 0 NOT NULL
);


ALTER TABLE carregis."CarID" OWNER TO dev;

--
-- TOC entry 4230 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN "CarID".carid; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "CarID".carid IS 'เลขที่ทะเบียนรถ';


--
-- TOC entry 214 (class 1259 OID 64747)
-- Dependencies: 3195 3196 7
-- Name: CarTaxDue; Type: TABLE; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE TABLE "CarTaxDue" (
    "IDCarTax" character varying(12) NOT NULL,
    "IDNO" character varying(25) NOT NULL,
    "TaxDueDate" date NOT NULL,
    "ApointmentDate" date,
    userid character varying(10),
    remark text,
    "CusAmt" double precision,
    cuspaid boolean DEFAULT false,
    "TypeDep" smallint,
    "BookIn" boolean DEFAULT false,
    "BookInDate" date
);


ALTER TABLE carregis."CarTaxDue" OWNER TO dev;

--
-- TOC entry 4231 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN "CarTaxDue"."IDNO"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "CarTaxDue"."IDNO" IS 'เลขที่สัญญาเช่าซื้อ';


--
-- TOC entry 4232 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN "CarTaxDue"."TaxDueDate"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "CarTaxDue"."TaxDueDate" IS 'วันครอบกำหนดตรวจมิเตอร์หรือภาษีรถ
และเป็น วันที่ทำรายการ ของค่าอื่นๆ';


--
-- TOC entry 4233 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN "CarTaxDue"."ApointmentDate"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "CarTaxDue"."ApointmentDate" IS 'วันที่นัดลูกค้าตรวจสภาพที่ขนส่ง
และเป็น วันที่เพิ่มใบเสร็จ ของค่าอื่นๆ';


--
-- TOC entry 4234 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN "CarTaxDue".userid; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "CarTaxDue".userid IS 'พนักงานบริษัทที่เป็นคนนัด';


--
-- TOC entry 4235 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN "CarTaxDue".remark; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "CarTaxDue".remark IS 'หมายเหตุอื่นๆ';


--
-- TOC entry 4236 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN "CarTaxDue"."CusAmt"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "CarTaxDue"."CusAmt" IS 'ยอดเงินที่เก็บกับลูกค้า';


--
-- TOC entry 4237 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN "CarTaxDue".cuspaid; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "CarTaxDue".cuspaid IS 'การชำระของลูกค้าให้บริษัท
ลูกค้าชำระแล้ว = true ยังไม่ชำระ = false';


--
-- TOC entry 215 (class 1259 OID 64755)
-- Dependencies: 3197 7
-- Name: DetailCarTax; Type: TABLE; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE TABLE "DetailCarTax" (
    "IDDetail" integer NOT NULL,
    "IDCarTax" character varying(12),
    "CoPayDate" date,
    "TaxValue" real,
    "TypePay" smallint,
    "BillNumber" text,
    "Cancel" boolean DEFAULT false
);


ALTER TABLE carregis."DetailCarTax" OWNER TO dev;

--
-- TOC entry 216 (class 1259 OID 64762)
-- Dependencies: 215 7
-- Name: DetailCarTax_IDDetail_seq; Type: SEQUENCE; Schema: carregis; Owner: dev
--

CREATE SEQUENCE "DetailCarTax_IDDetail_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE carregis."DetailCarTax_IDDetail_seq" OWNER TO dev;

--
-- TOC entry 4238 (class 0 OID 0)
-- Dependencies: 216
-- Name: DetailCarTax_IDDetail_seq; Type: SEQUENCE OWNED BY; Schema: carregis; Owner: dev
--

ALTER SEQUENCE "DetailCarTax_IDDetail_seq" OWNED BY "DetailCarTax"."IDDetail";


--
-- TOC entry 217 (class 1259 OID 64764)
-- Dependencies: 7
-- Name: LogID_seq; Type: SEQUENCE; Schema: carregis; Owner: dev
--

CREATE SEQUENCE "LogID_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE carregis."LogID_seq" OWNER TO dev;

--
-- TOC entry 4239 (class 0 OID 0)
-- Dependencies: 217
-- Name: SEQUENCE "LogID_seq"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON SEQUENCE "LogID_seq" IS 'ลำดับ';


--
-- TOC entry 218 (class 1259 OID 64766)
-- Dependencies: 3199 7
-- Name: LogRegisChange; Type: TABLE; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE TABLE "LogRegisChange" (
    "LogID" integer DEFAULT nextval('"LogID_seq"'::regclass) NOT NULL,
    "UserID" character varying(10),
    "IDNO" character varying(25),
    "AssetID" character varying(10),
    "IDDetail" integer,
    "FieldChanged" character varying(25),
    "TypePay" smallint,
    "OldValue" character varying(90),
    "NewValue" character varying(20),
    "DatetimeChanged" timestamp without time zone
);


ALTER TABLE carregis."LogRegisChange" OWNER TO dev;

--
-- TOC entry 4240 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN "LogRegisChange"."UserID"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "LogRegisChange"."UserID" IS 'User ที่ทำรายการ';


--
-- TOC entry 4241 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN "LogRegisChange"."IDNO"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "LogRegisChange"."IDNO" IS 'รหัสสัญญาเช่าซื้อ';


--
-- TOC entry 4242 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN "LogRegisChange"."AssetID"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "LogRegisChange"."AssetID" IS 'รหัสสินทรัพย์';


--
-- TOC entry 4243 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN "LogRegisChange"."IDDetail"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "LogRegisChange"."IDDetail" IS 'ID ของค่าใช้จ่ายขนส่งอื่นๆ';


--
-- TOC entry 4244 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN "LogRegisChange"."FieldChanged"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "LogRegisChange"."FieldChanged" IS 'Field ที่มีการ update ค่าใหม่';


--
-- TOC entry 4245 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN "LogRegisChange"."TypePay"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "LogRegisChange"."TypePay" IS 'คำอธิบายรายการจ่าย';


--
-- TOC entry 219 (class 1259 OID 64770)
-- Dependencies: 7
-- Name: TrafficPenalty; Type: TABLE; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE TABLE "TrafficPenalty" (
    "IDTFPEN" character varying(10) NOT NULL,
    "IDNO" character varying(25) NOT NULL,
    asset_id character varying(10),
    "IssueDate" date NOT NULL,
    "ReceivedUser" smallint NOT NULL,
    "AckUser" smallint,
    "AckType" character varying(3),
    "AckMoney" double precision,
    receipt_id character varying(12),
    "Acked" boolean,
    "AckDate" timestamp without time zone,
    "Status" character varying(1)
);


ALTER TABLE carregis."TrafficPenalty" OWNER TO dev;

--
-- TOC entry 4246 (class 0 OID 0)
-- Dependencies: 219
-- Name: TABLE "TrafficPenalty"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON TABLE "TrafficPenalty" IS 'Table สำหรับจัดการเรื่องค่าปรับจราจร';


--
-- TOC entry 4247 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."IDTFPEN"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."IDTFPEN" IS 'รหัสการแจ้งค่าปรับจราจรไฟแดง';


--
-- TOC entry 4248 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."IDNO"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."IDNO" IS 'เลขที่สัญญาเช่าซื้อ';


--
-- TOC entry 4249 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty".asset_id; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty".asset_id IS 'รหัสสินทรัพย์';


--
-- TOC entry 4250 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."IssueDate"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."IssueDate" IS 'วันที่ออกใบสั่ง';


--
-- TOC entry 4251 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."ReceivedUser"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."ReceivedUser" IS 'รหัสผู้รับเรื่อง';


--
-- TOC entry 4252 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."AckUser"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."AckUser" IS 'รหัสผู้แจ้งเรื่อง';


--
-- TOC entry 4253 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."AckType"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."AckType" IS 'รูปแบบการแจ้ง';


--
-- TOC entry 4254 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."AckMoney"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."AckMoney" IS 'จำนวนเงินที่เก็บลูกค้า';


--
-- TOC entry 4255 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty".receipt_id; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty".receipt_id IS 'ใบเสร็จที่ออกเพื่อชำระค่าบริการ';


--
-- TOC entry 4256 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."Acked"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."Acked" IS 'ได้มีการแจ้งเตือนลูกค้าแล้ว';


--
-- TOC entry 4257 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."AckDate"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."AckDate" IS 'วันที่เวลาที่แจ้งเตือน';


--
-- TOC entry 4258 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN "TrafficPenalty"."Status"; Type: COMMENT; Schema: carregis; Owner: dev
--

COMMENT ON COLUMN "TrafficPenalty"."Status" IS 'สถานะของงาน';


SET search_path = corporate, pg_catalog;

--
-- TOC entry 220 (class 1259 OID 64773)
-- Dependencies: 3200 8
-- Name: CReceiptNO; Type: TABLE; Schema: corporate; Owner: dev; Tablespace: 
--

CREATE TABLE "CReceiptNO" (
    i_year integer NOT NULL,
    i_month integer NOT NULL,
    "I" integer DEFAULT 0 NOT NULL
);


ALTER TABLE corporate."CReceiptNO" OWNER TO dev;

--
-- TOC entry 221 (class 1259 OID 64777)
-- Dependencies: 8
-- Name: type_corp; Type: TABLE; Schema: corporate; Owner: dev; Tablespace: 
--

CREATE TABLE type_corp (
    contact_code character varying(2) NOT NULL,
    dtl_code character varying(50),
    amt double precision,
    sign_amt double precision
);


ALTER TABLE corporate.type_corp OWNER TO dev;

SET search_path = public, pg_catalog;

--
-- TOC entry 222 (class 1259 OID 64780)
-- Dependencies: 3201 3202 3203 15
-- Name: FpOutCus; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FpOutCus" (
    "IDNO" character varying(25) NOT NULL,
    "CusID" character varying(12) NOT NULL,
    "CarID" character varying(12) NOT NULL,
    "OCRef1" character varying(15),
    "OCRef2" character varying(15),
    "ACStartDate" date,
    "ACCloseDate" date,
    "AcClose" boolean DEFAULT false,
    "TranferIDNO" character varying(12),
    "SignDate" date,
    "TypeContact" character varying(2) DEFAULT '00'::character varying,
    old_id character varying(25),
    asset_type smallint DEFAULT 3
);


ALTER TABLE public."FpOutCus" OWNER TO dev;

SET search_path = corporate, pg_catalog;

--
-- TOC entry 223 (class 1259 OID 64786)
-- Dependencies: 3082 8
-- Name: VCorpContact; Type: VIEW; Schema: corporate; Owner: dev
--

CREATE VIEW "VCorpContact" AS
    SELECT c."IDNO", c."CusID", c."CarID", c."OCRef1", c."OCRef2", c."ACStartDate", c."ACCloseDate", c."AcClose", c."TranferIDNO", c."SignDate", c."TypeContact", t.amt, t.sign_amt FROM public."FpOutCus" c, type_corp t WHERE (((c."TypeContact")::text = (t.contact_code)::text) AND ((c."TypeContact")::text <> '00'::text));


ALTER TABLE corporate."VCorpContact" OWNER TO dev;

--
-- TOC entry 224 (class 1259 OID 64790)
-- Dependencies: 8
-- Name: corpinvoice; Type: TABLE; Schema: corporate; Owner: dev; Tablespace: 
--

CREATE TABLE corpinvoice (
    inv_no character varying(15) NOT NULL,
    "IDNO" character varying(25),
    "DueDate" date,
    amt double precision,
    "PrnDate" date,
    "Cancel" boolean,
    "RefReceipt" character varying(12)
);


ALTER TABLE corporate.corpinvoice OWNER TO dev;

SET search_path = public, pg_catalog;

--
-- TOC entry 225 (class 1259 OID 64793)
-- Dependencies: 3204 15
-- Name: FOtherpay; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FOtherpay" (
    "IDNO" character varying(25) NOT NULL,
    "O_DATE" date,
    "O_RECEIPT" character varying(25) NOT NULL,
    "O_MONEY" double precision,
    "O_Type" smallint NOT NULL,
    "O_BANK" character varying(10),
    "O_PRNDATE" date,
    "PayType" character varying(10),
    "Cancel" boolean DEFAULT false,
    "O_memo" text,
    "RefAnyID" character varying(12)
);


ALTER TABLE public."FOtherpay" OWNER TO dev;

SET search_path = corporate, pg_catalog;

--
-- TOC entry 226 (class 1259 OID 64800)
-- Dependencies: 3083 8
-- Name: VCorpDetail; Type: VIEW; Schema: corporate; Owner: dev
--

CREATE VIEW "VCorpDetail" AS
    SELECT corpinvoice.inv_no, corpinvoice."IDNO", corpinvoice."DueDate", corpinvoice.amt, "FOtherpay"."O_DATE", "FOtherpay"."O_RECEIPT", "FOtherpay"."O_Type", "FOtherpay"."O_BANK", "FOtherpay"."PayType" FROM (corpinvoice LEFT JOIN public."FOtherpay" ON ((((corpinvoice."RefReceipt")::text = ("FOtherpay"."O_RECEIPT")::text) AND (corpinvoice."Cancel" = false)))) ORDER BY corpinvoice."IDNO", corpinvoice."DueDate";


ALTER TABLE corporate."VCorpDetail" OWNER TO dev;

SET search_path = finance, pg_catalog;

--
-- TOC entry 397 (class 1259 OID 66811)
-- Dependencies: 3338 3339 3340 14
-- Name: thcap_receive_cheque; Type: TABLE; Schema: finance; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receive_cheque (
    "revChqID" character varying(20) NOT NULL,
    "cnID" character varying(10) NOT NULL,
    "revChqFlowStatus" smallint DEFAULT 0 NOT NULL,
    "revChqStatus" smallint DEFAULT 9 NOT NULL,
    "revChqDate" date NOT NULL,
    "revChqToCCID" character varying(35) NOT NULL,
    "bankChqNo" character varying(20) NOT NULL,
    "bankChqDate" date NOT NULL,
    "bankChqType" smallint DEFAULT 9 NOT NULL,
    "bankChqToCompID" character varying(30) NOT NULL,
    "bankOutID" character varying(20) NOT NULL,
    "bankOutBranch" character varying(20) NOT NULL,
    "bankChqAmt" numeric(15,2) NOT NULL,
    "pathID" character varying,
    "revChqRemask" character varying,
    "bankOutRegion" smallint NOT NULL
);


ALTER TABLE finance.thcap_receive_cheque OWNER TO dev;

--
-- TOC entry 4259 (class 0 OID 0)
-- Dependencies: 397
-- Name: COLUMN thcap_receive_cheque."cnID"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque."cnID" IS 'ประเภทการนำเข้า (โอน / เช็ค / bill payment)';


--
-- TOC entry 4260 (class 0 OID 0)
-- Dependencies: 397
-- Name: COLUMN thcap_receive_cheque."revChqStatus"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque."revChqStatus" IS '0 - (เช็คถูกยกเลิก อาจจะไม่ได้รับจริง หรือข้อมูลผิด)
1 - ACTIVE (ผลเช็คผ่านแล้ว)
2 - เช็คตีคืน (ผลเช็คเด้ง)
3 - คืนเช็คให้ลูกค้า
4 - ไม่ได้รับเช็ค / เช็คหายไม่ทราบสาเหตุ
6 - เช็คถูกเข้าธนาคารแล้วลงวันที่ รอตรวจสอบผลเช็ค
7 - เช็คถูกนำไปเข้า
8 - เช็คที่รับถูกส่งให้ผู้เก็บเช็ค
9 - พนักงานรับเช็ค';


--
-- TOC entry 4261 (class 0 OID 0)
-- Dependencies: 397
-- Name: COLUMN thcap_receive_cheque."bankChqType"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque."bankChqType" IS '1 - เช็คทั่วไป
2 - เช็คธนาคาร / Manager cheque
9 - ไม่ระบุ';


--
-- TOC entry 402 (class 1259 OID 67060)
-- Dependencies: 3342 14
-- Name: thcap_receive_cheque_keeper; Type: TABLE; Schema: finance; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receive_cheque_keeper (
    "chqKeeperID" bigint NOT NULL,
    "revChqID" character varying(20) NOT NULL,
    "keepChqDate" date,
    "keepFrom" smallint NOT NULL,
    "keeperID" character varying(12) NOT NULL,
    "keeperStamp" timestamp without time zone NOT NULL,
    "giveTakerID" character varying(12),
    "giveTakerStamp" timestamp without time zone,
    "giveTakerDate" date,
    "giveTakerToBankAcc" character varying(25),
    "replyByTakerID" character varying(12),
    "replyByTakerStamp" timestamp without time zone,
    "bankRevDate" date,
    "bankRevResult" smallint,
    "getBankSlip" smallint,
    "giveCusConID" character varying(25),
    "giveCusDate" date,
    "receiptIDForReturn" character varying(30),
    CONSTRAINT "giveTakerOrCus" CHECK ((("giveTakerID" IS NULL) OR ("giveCusConID" IS NULL)))
);


ALTER TABLE finance.thcap_receive_cheque_keeper OWNER TO dev;

--
-- TOC entry 4262 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."chqKeeperID"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."chqKeeperID" IS 'auto running เพื่อบอกรายการที่เกิดก่อนหลัง';


--
-- TOC entry 4263 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."revChqID"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."revChqID" IS 'รหัสเช็ค';


--
-- TOC entry 4264 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."keepChqDate"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."keepChqDate" IS 'วันที่ผู้เก็บเช็ค รับเช็ค';


--
-- TOC entry 4265 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."keepFrom"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."keepFrom" IS 'ช่องทางการรับเช็ค
1 - รับชำระ
2 - เช็คที่เคยตีคืนกลับ (เช็คเด้ง)';


--
-- TOC entry 4266 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."keeperID"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."keeperID" IS 'รหัสผู้เก็บเช็ค';


--
-- TOC entry 4267 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."keeperStamp"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."keeperStamp" IS 'วันเวลาที่กดยืนยันการรับเช็ค';


--
-- TOC entry 4268 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."giveTakerID"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."giveTakerID" IS 'ผู้เก็บเช็คมอบให้ผู้นำเช็ค ID เพื่อเข้า bank';


--
-- TOC entry 4269 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."giveTakerDate"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."giveTakerDate" IS 'วันที่มอบเช็คให้กับผู้นำเช็คไปขึ้น';


--
-- TOC entry 4270 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."giveTakerToBankAcc"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."giveTakerToBankAcc" IS 'บัญชีที่นำเช็คไปเข้า';


--
-- TOC entry 4271 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."bankRevDate"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."bankRevDate" IS 'วันที่ bank ลงรับเช็ค';


--
-- TOC entry 4272 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."bankRevResult"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."bankRevResult" IS 'ผลการลงรับเช็คของ bank
1 - ปกติ
2 - too late';


--
-- TOC entry 4273 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."getBankSlip"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."getBankSlip" IS 'ได้รับ slip แล้ว
1 - ได้รับ
2 - ไม่ได้รับ';


--
-- TOC entry 4274 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."giveCusConID"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."giveCusConID" IS 'รหัสสัญญาที่คืนเช็คให้ลูกค้า';


--
-- TOC entry 4275 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."giveCusDate"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."giveCusDate" IS 'วันที่คืนเช็คให้ลูกค้า';


--
-- TOC entry 4276 (class 0 OID 0)
-- Dependencies: 402
-- Name: COLUMN thcap_receive_cheque_keeper."receiptIDForReturn"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_cheque_keeper."receiptIDForReturn" IS 'เลขที่ใบเสร็จที่เอาเงินมาจ่ายแลกเช็คคืน';


--
-- TOC entry 403 (class 1259 OID 67072)
-- Dependencies: 3115 14
-- Name: V_thcap_receive_cheque_chqManage; Type: VIEW; Schema: finance; Owner: dev
--

CREATE VIEW "V_thcap_receive_cheque_chqManage" AS
    SELECT thcap_receive_cheque_keeper."chqKeeperID", thcap_receive_cheque."revChqID", thcap_receive_cheque."bankChqNo", thcap_receive_cheque."bankChqDate", thcap_receive_cheque."bankOutID", thcap_receive_cheque."bankOutBranch", thcap_receive_cheque."bankChqToCompID", thcap_receive_cheque."bankChqAmt", thcap_receive_cheque_keeper."keepChqDate", thcap_receive_cheque_keeper."keeperID", thcap_receive_cheque_keeper."giveTakerID", thcap_receive_cheque_keeper."giveTakerStamp", thcap_receive_cheque_keeper."giveTakerDate", thcap_receive_cheque_keeper."giveTakerToBankAcc", thcap_receive_cheque_keeper."bankRevDate", thcap_receive_cheque_keeper."bankRevResult", thcap_receive_cheque_keeper."getBankSlip", thcap_receive_cheque."revChqStatus", thcap_receive_cheque."revChqDate" FROM (thcap_receive_cheque LEFT JOIN thcap_receive_cheque_keeper ON (((thcap_receive_cheque."revChqID")::text = (thcap_receive_cheque_keeper."revChqID")::text)));


ALTER TABLE finance."V_thcap_receive_cheque_chqManage" OWNER TO dev;

--
-- TOC entry 394 (class 1259 OID 66674)
-- Dependencies: 3334 3335 14
-- Name: thcap_receive_transfer; Type: TABLE; Schema: finance; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receive_transfer (
    "revTranID" character varying(20) NOT NULL,
    "cnID" character varying(10) NOT NULL,
    "revTranFlowStatus" smallint DEFAULT 0 NOT NULL,
    "revTranStatus" smallint DEFAULT 9 NOT NULL,
    "bankRevAccID" character varying(20) NOT NULL,
    "bankRevBranch" character varying(20) NOT NULL,
    "bankRevStamp" timestamp without time zone NOT NULL,
    "bankRevRef1" character varying(20),
    "bankRevRef2" character varying(20),
    "bankRevRefName" character varying(100),
    "bankRevAmt" numeric(15,2) NOT NULL
);


ALTER TABLE finance.thcap_receive_transfer OWNER TO dev;

--
-- TOC entry 4277 (class 0 OID 0)
-- Dependencies: 394
-- Name: COLUMN thcap_receive_transfer."cnID"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer."cnID" IS 'ประเภทการนำเข้า (โอน / เช็ค / bill payment)';


--
-- TOC entry 4278 (class 0 OID 0)
-- Dependencies: 394
-- Name: COLUMN thcap_receive_transfer."revTranStatus"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer."revTranStatus" IS '0 - DELETED
1 - ACTIVE
9 - WAIT FOR APPROVE';


--
-- TOC entry 4279 (class 0 OID 0)
-- Dependencies: 394
-- Name: COLUMN thcap_receive_transfer."bankRevAccID"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer."bankRevAccID" IS 'เลขที่บัญชีบริษัทที่รับจาก public.bankInt';


--
-- TOC entry 4280 (class 0 OID 0)
-- Dependencies: 394
-- Name: COLUMN thcap_receive_transfer."bankRevStamp"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer."bankRevStamp" IS '*** วันที่และเวลาที่เงินเข้า bank';


--
-- TOC entry 396 (class 1259 OID 66701)
-- Dependencies: 3337 14
-- Name: thcap_receive_transfer_action; Type: TABLE; Schema: finance; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receive_transfer_action (
    "tranActionID" bigint NOT NULL,
    "tranActionType" character varying(10) NOT NULL,
    "revTranID" character varying(20) NOT NULL,
    "serialAction" smallint DEFAULT 1 NOT NULL,
    "doerID" character varying(12) NOT NULL,
    "doerStamp" timestamp without time zone NOT NULL,
    "doerRemask" character varying,
    "appvXID" character varying(12),
    "appvXStamp" timestamp without time zone,
    "appvXRemask" character varying,
    "appvXStatus" smallint,
    "appvYID" character varying(12),
    "appvYStamp" timestamp without time zone,
    "appvYRemask" character varying,
    "appvYStatus" smallint,
    "auditorXID" character varying(12),
    "auditorXStamp" timestamp without time zone,
    "auditorXRemask" character varying,
    "auditorXStatus" smallint,
    "auditorYID" character varying(12),
    "auditorYStamp" timestamp without time zone,
    "auditorYRemask" character varying,
    "auditorYStatus" smallint
);


ALTER TABLE finance.thcap_receive_transfer_action OWNER TO dev;

--
-- TOC entry 4281 (class 0 OID 0)
-- Dependencies: 396
-- Name: COLUMN thcap_receive_transfer_action."tranActionType"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer_action."tranActionType" IS 'I - Input
C - Cancel';


--
-- TOC entry 4282 (class 0 OID 0)
-- Dependencies: 396
-- Name: COLUMN thcap_receive_transfer_action."revTranID"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer_action."revTranID" IS 'รหัสรายการเงินโอนที่ link เชื่อมกัน';


--
-- TOC entry 4283 (class 0 OID 0)
-- Dependencies: 396
-- Name: COLUMN thcap_receive_transfer_action."appvXStatus"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer_action."appvXStatus" IS '0-ไม่อนุมัติ
1-อนุมัติ';


--
-- TOC entry 4284 (class 0 OID 0)
-- Dependencies: 396
-- Name: COLUMN thcap_receive_transfer_action."appvYStatus"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer_action."appvYStatus" IS '0-ไม่อนุมัติ
1-อนุมัติ';


--
-- TOC entry 4285 (class 0 OID 0)
-- Dependencies: 396
-- Name: COLUMN thcap_receive_transfer_action."auditorXStatus"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer_action."auditorXStatus" IS '0-ไม่ถูกต้อง
1-ถูกต้อง';


--
-- TOC entry 4286 (class 0 OID 0)
-- Dependencies: 396
-- Name: COLUMN thcap_receive_transfer_action."auditorYStatus"; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON COLUMN thcap_receive_transfer_action."auditorYStatus" IS '0-ไม่ถูกต้อง
1-ถูกต้อง';


--
-- TOC entry 400 (class 1259 OID 67029)
-- Dependencies: 3114 14
-- Name: V_thcap_receive_transfer_tsfAppv; Type: VIEW; Schema: finance; Owner: dev
--

CREATE VIEW "V_thcap_receive_transfer_tsfAppv" AS
    SELECT thcap_receive_transfer."revTranID", thcap_receive_transfer."cnID", thcap_receive_transfer."revTranStatus", thcap_receive_transfer."bankRevAccID", thcap_receive_transfer."bankRevBranch", thcap_receive_transfer."bankRevStamp", thcap_receive_transfer."bankRevAmt", thcap_receive_transfer_action."doerStamp", thcap_receive_transfer_action."appvXStamp", thcap_receive_transfer_action."appvXStatus", thcap_receive_transfer_action."appvXRemask", thcap_receive_transfer_action."appvYStamp", thcap_receive_transfer_action."appvYStatus", thcap_receive_transfer_action."appvYRemask", thcap_receive_transfer_action."tranActionType", thcap_receive_transfer_action."tranActionID" FROM (thcap_receive_transfer LEFT JOIN thcap_receive_transfer_action ON ((((thcap_receive_transfer."revTranID")::text = (thcap_receive_transfer_action."revTranID")::text) AND ((thcap_receive_transfer."cnID")::text = 'TSF'::text))));


ALTER TABLE finance."V_thcap_receive_transfer_tsfAppv" OWNER TO dev;

--
-- TOC entry 399 (class 1259 OID 66950)
-- Dependencies: 14
-- Name: thcap_receive_cheque_detials; Type: TABLE; Schema: finance; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receive_cheque_detials (
    "revChqID" character varying(20) NOT NULL,
    "receiverFullName" character varying(120) NOT NULL,
    "receiverID" character varying(12) NOT NULL,
    "receiverStamp" timestamp without time zone NOT NULL
);


ALTER TABLE finance.thcap_receive_cheque_detials OWNER TO dev;

--
-- TOC entry 401 (class 1259 OID 67058)
-- Dependencies: 14 402
-- Name: thcap_receive_cheque_keeper_chqKeeperID_seq; Type: SEQUENCE; Schema: finance; Owner: dev
--

CREATE SEQUENCE "thcap_receive_cheque_keeper_chqKeeperID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE finance."thcap_receive_cheque_keeper_chqKeeperID_seq" OWNER TO dev;

--
-- TOC entry 4287 (class 0 OID 0)
-- Dependencies: 401
-- Name: thcap_receive_cheque_keeper_chqKeeperID_seq; Type: SEQUENCE OWNED BY; Schema: finance; Owner: dev
--

ALTER SEQUENCE "thcap_receive_cheque_keeper_chqKeeperID_seq" OWNED BY thcap_receive_cheque_keeper."chqKeeperID";


--
-- TOC entry 395 (class 1259 OID 66699)
-- Dependencies: 14 396
-- Name: thcap_receive_transfer_action_tranActionID_seq; Type: SEQUENCE; Schema: finance; Owner: dev
--

CREATE SEQUENCE "thcap_receive_transfer_action_tranActionID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE finance."thcap_receive_transfer_action_tranActionID_seq" OWNER TO dev;

--
-- TOC entry 4288 (class 0 OID 0)
-- Dependencies: 395
-- Name: thcap_receive_transfer_action_tranActionID_seq; Type: SEQUENCE OWNED BY; Schema: finance; Owner: dev
--

ALTER SEQUENCE "thcap_receive_transfer_action_tranActionID_seq" OWNED BY thcap_receive_transfer_action."tranActionID";


--
-- TOC entry 391 (class 1259 OID 66609)
-- Dependencies: 14
-- Name: thcap_receive_transfer_chq; Type: TABLE; Schema: finance; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receive_transfer_chq (
);


ALTER TABLE finance.thcap_receive_transfer_chq OWNER TO dev;

--
-- TOC entry 392 (class 1259 OID 66612)
-- Dependencies: 14
-- Name: thcap_receive_transfer_int; Type: TABLE; Schema: finance; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_receive_transfer_int (
);


ALTER TABLE finance.thcap_receive_transfer_int OWNER TO dev;

SET search_path = gas, pg_catalog;

--
-- TOC entry 227 (class 1259 OID 64804)
-- Dependencies: 9
-- Name: Company; Type: TABLE; Schema: gas; Owner: dev; Tablespace: 
--

CREATE TABLE "Company" (
    coid character varying(10) NOT NULL,
    coname character varying(100),
    address text,
    phone character varying(50),
    acid character varying(5)
);


ALTER TABLE gas."Company" OWNER TO dev;

--
-- TOC entry 228 (class 1259 OID 64810)
-- Dependencies: 3205 3206 3207 9
-- Name: GasID; Type: TABLE; Schema: gas; Owner: dev; Tablespace: 
--

CREATE TABLE "GasID" (
    branch smallint DEFAULT 1 NOT NULL,
    monthid date NOT NULL,
    poid smallint DEFAULT 0,
    payid smallint DEFAULT 0
);


ALTER TABLE gas."GasID" OWNER TO dev;

--
-- TOC entry 229 (class 1259 OID 64816)
-- Dependencies: 3208 3209 3210 9
-- Name: Model; Type: TABLE; Schema: gas; Owner: dev; Tablespace: 
--

CREATE TABLE "Model" (
    modelid character varying(5) NOT NULL,
    coid character varying(5),
    modelname character varying(50),
    costofgas double precision DEFAULT 0,
    price_tank double precision DEFAULT 0,
    price_device double precision DEFAULT 0
);


ALTER TABLE gas."Model" OWNER TO dev;

--
-- TOC entry 230 (class 1259 OID 64822)
-- Dependencies: 3211 3212 3213 9
-- Name: PayToGas; Type: TABLE; Schema: gas; Owner: dev; Tablespace: 
--

CREATE TABLE "PayToGas" (
    payid character varying(15) NOT NULL,
    dodate date,
    cash double precision DEFAULT 0,
    "CQBank" character varying(15),
    "CQID" character varying(15),
    "CQDate" date,
    "CQAmt" double precision DEFAULT 0,
    "Cancel" boolean DEFAULT false NOT NULL,
    "Remark" text,
    idmaker character varying(10),
    idauthority character varying(10)
);


ALTER TABLE gas."PayToGas" OWNER TO dev;

--
-- TOC entry 231 (class 1259 OID 64831)
-- Dependencies: 3214 3215 3216 3217 3218 9
-- Name: PoGas; Type: TABLE; Schema: gas; Owner: dev; Tablespace: 
--

CREATE TABLE "PoGas" (
    poid character varying(15) NOT NULL,
    idno character varying(25),
    podate date,
    date_install date,
    idcompany character varying(10),
    idmodel character varying(10),
    costofgas double precision DEFAULT 0,
    vatofcost double precision DEFAULT 0,
    bill character varying(15),
    invoice character varying(15),
    status_po boolean DEFAULT true,
    status_pay boolean DEFAULT false,
    status_approve boolean DEFAULT false,
    memo text,
    payid character varying(15),
    carnum character varying(22),
    marnum character varying(20),
    vat_date date
);


ALTER TABLE gas."PoGas" OWNER TO dev;

--
-- TOC entry 232 (class 1259 OID 64842)
-- Dependencies: 3084 9
-- Name: VCusPay; Type: VIEW; Schema: gas; Owner: dev
--

CREATE VIEW "VCusPay" AS
    SELECT "PoGas".poid, "PoGas".idno, "FVat"."V_DueNo", "Fr"."R_Date", "Fr"."R_Receipt", ("Fr"."R_Money" + "FVat"."VatValue") AS amount, "FOtherpay"."O_DATE", "FOtherpay"."O_RECEIPT", "FOtherpay"."O_MONEY" FROM ((("PoGas" LEFT JOIN public."Fr" ON (((("PoGas".idno)::text = ("Fr"."IDNO")::text) AND (("PoGas".poid)::text = ("Fr"."RefAnyID")::text)))) LEFT JOIN public."FOtherpay" ON (((("PoGas".idno)::text = ("FOtherpay"."IDNO")::text) AND (("PoGas".poid)::text = ("FOtherpay"."RefAnyID")::text)))) LEFT JOIN public."FVat" ON (((("PoGas".idno)::text = ("FVat"."IDNO")::text) AND (("FVat"."V_DueNo" = 0) OR ("FVat"."V_DueNo" = 99))))) ORDER BY "PoGas".poid;


ALTER TABLE gas."VCusPay" OWNER TO dev;

SET search_path = insure, pg_catalog;

--
-- TOC entry 233 (class 1259 OID 64847)
-- Dependencies: 3219 10
-- Name: Commision; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE "Commision" (
    "InsCompany" character varying(5) NOT NULL,
    "CommCode" character varying(3) NOT NULL,
    "UsePercent" boolean NOT NULL,
    "Commision" real NOT NULL,
    "NameOfCode" character varying(30),
    "TypeUnForce" boolean DEFAULT true NOT NULL
);


ALTER TABLE insure."Commision" OWNER TO dev;

--
-- TOC entry 4289 (class 0 OID 0)
-- Dependencies: 233
-- Name: COLUMN "Commision"."TypeUnForce"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "Commision"."TypeUnForce" IS 'เป็น พรบ.=false, เป็นประกันสมัครใจ = true';


--
-- TOC entry 234 (class 1259 OID 64851)
-- Dependencies: 3220 3221 3222 3223 3224 3225 3226 3227 3228 3229 3230 10
-- Name: InsureForce; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE "InsureForce" (
    "InsFIDNO" character varying(12) NOT NULL,
    "IDNO" character varying(25),
    "CusID" character varying(12),
    "CarID" character varying(12),
    "InsID" character varying(25),
    "InsMark" character varying(20),
    "Company" character varying(5) NOT NULL,
    "StartDate" date NOT NULL,
    "EndDate" date NOT NULL,
    "Code" character varying(5) NOT NULL,
    "Capacity" character varying(5),
    "Premium" real DEFAULT 0 NOT NULL,
    "NetPremium" real DEFAULT 0 NOT NULL,
    "Vat" real DEFAULT 0 NOT NULL,
    "TaxStamp" smallint DEFAULT 0 NOT NULL,
    "DoDate" date,
    "Discount" real DEFAULT 0 NOT NULL,
    "CollectCus" real DEFAULT 0 NOT NULL,
    "CusPayReady" boolean DEFAULT false NOT NULL,
    "Cancel" boolean DEFAULT false NOT NULL,
    "Commision" real DEFAULT 0 NOT NULL,
    "CoPayInsAmt" real DEFAULT 0 NOT NULL,
    "CoPayInsReady" boolean DEFAULT false NOT NULL,
    "CoPayInsID" character varying(12),
    "Remark" text
);


ALTER TABLE insure."InsureForce" OWNER TO dev;

--
-- TOC entry 431 (class 1259 OID 68869)
-- Dependencies: 3414 3415 3416 3417 3418 3419 3420 3421 3422 3423 3424 10
-- Name: InsureForce_Backup; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE "InsureForce_Backup" (
    "InsFIDNO" character varying(12) NOT NULL,
    "IDNO" character varying(25),
    "CusID" character varying(12),
    "CarID" character varying(12),
    "InsID" character varying(25),
    "InsMark" character varying(20),
    "Company" character varying(5) NOT NULL,
    "StartDate" date NOT NULL,
    "EndDate" date NOT NULL,
    "Code" character varying(5) NOT NULL,
    "Capacity" character varying(5),
    "Premium" real DEFAULT 0 NOT NULL,
    "NetPremium" real DEFAULT 0 NOT NULL,
    "Vat" real DEFAULT 0 NOT NULL,
    "TaxStamp" smallint DEFAULT 0 NOT NULL,
    "DoDate" date,
    "Discount" real DEFAULT 0 NOT NULL,
    "CollectCus" real DEFAULT 0 NOT NULL,
    "CusPayReady" boolean DEFAULT false NOT NULL,
    "Cancel" boolean DEFAULT false NOT NULL,
    "Commision" real DEFAULT 0 NOT NULL,
    "CoPayInsAmt" real DEFAULT 0 NOT NULL,
    "CoPayInsReady" boolean DEFAULT false NOT NULL,
    "CoPayInsID" character varying(12),
    "Remark" text
);


ALTER TABLE insure."InsureForce_Backup" OWNER TO dev;

--
-- TOC entry 235 (class 1259 OID 64868)
-- Dependencies: 3231 3232 3233 3234 10
-- Name: InsureID; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE "InsureID" (
    branch smallint DEFAULT 1 NOT NULL,
    monthid date NOT NULL,
    forceid smallint DEFAULT 0 NOT NULL,
    unforceid smallint DEFAULT 0 NOT NULL,
    payid smallint DEFAULT 0 NOT NULL
);


ALTER TABLE insure."InsureID" OWNER TO dev;

--
-- TOC entry 4290 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN "InsureID".forceid; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureID".forceid IS 'เลขที่กรมธรรม์ พรบ.';


--
-- TOC entry 4291 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN "InsureID".unforceid; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureID".unforceid IS 'เลขที่กรมธรรม์ภาคสมัครใจ';


--
-- TOC entry 4292 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN "InsureID".payid; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureID".payid IS 'เลขที่การชำระเงินให้บริษัทประกัน';


--
-- TOC entry 236 (class 1259 OID 64875)
-- Dependencies: 10
-- Name: InsureInfo; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE "InsureInfo" (
    "InsCompany" character varying(10) NOT NULL,
    "InsFullName" character varying(50) NOT NULL,
    "CoCode" character varying(20) NOT NULL
);


ALTER TABLE insure."InsureInfo" OWNER TO dev;

--
-- TOC entry 237 (class 1259 OID 64878)
-- Dependencies: 3235 3236 3237 3238 3239 3240 3241 3242 3243 3244 10
-- Name: InsureUnforce; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE "InsureUnforce" (
    "InsUFIDNO" character varying(12) NOT NULL,
    "IDNO" character varying(25),
    "CusID" character varying(12) NOT NULL,
    "CarID" character varying(12) NOT NULL,
    "InsID" character varying(25),
    "TempInsID" character varying(25),
    "Company" character varying(5) NOT NULL,
    "StartDate" date NOT NULL,
    "Code" character varying(5) NOT NULL,
    "Kind" character varying(2) NOT NULL,
    "Invest" integer DEFAULT 0 NOT NULL,
    "Premium" double precision DEFAULT 0 NOT NULL,
    "NetPremium" double precision DEFAULT 0 NOT NULL,
    "ConfirmDate" date NOT NULL,
    "Discount" real DEFAULT 0 NOT NULL,
    "CollectCus" double precision DEFAULT 0 NOT NULL,
    "InsUser" character varying(20),
    "Remark" text,
    "CusPayReady" boolean DEFAULT false NOT NULL,
    "Cancel" boolean DEFAULT false NOT NULL,
    "Commision" real DEFAULT 0 NOT NULL,
    "CoPayInsAmt" double precision DEFAULT 0 NOT NULL,
    "CoPayInsReady" boolean DEFAULT false NOT NULL,
    "CoPayInsID" character varying(12),
    "EndDate" date,
    "InsDate" date
);


ALTER TABLE insure."InsureUnforce" OWNER TO dev;

--
-- TOC entry 4293 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."InsUFIDNO"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."InsUFIDNO" IS 'เลขที่ภายในของบริษัท IF0812001';


--
-- TOC entry 4294 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."IDNO"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."IDNO" IS 'เลขที่สัญญาเช่าซื้อ จาก Fp';


--
-- TOC entry 4295 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."CusID"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."CusID" IS 'เลขที่ของลูกค้า จาก fa1';


--
-- TOC entry 4296 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."InsID"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."InsID" IS 'เลขที่ของกรมธรรม์';


--
-- TOC entry 4297 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."TempInsID"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."TempInsID" IS 'เลขรับแจ้งใช้ชั่วคราวขณะที่เลขกรมธรรม์ยังไม่ออก';


--
-- TOC entry 4298 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."Company"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."Company" IS 'รหัสบริษัทประกันภัย';


--
-- TOC entry 4299 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."StartDate"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."StartDate" IS 'วันที่เริ่มคุ้มครองในกรมธรรม์';


--
-- TOC entry 4300 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."Code"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."Code" IS 'รหัสประเภทของรถที่คุ้มครอง';


--
-- TOC entry 4301 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."Kind"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."Kind" IS 'ประเภทของแระภัน เช่น 1,2,3, T1,T2,T3';


--
-- TOC entry 4302 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."Invest"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."Invest" IS 'ทุนประภัน';


--
-- TOC entry 4303 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."Premium"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."Premium" IS 'เบี้ยประกันทั้งหมด';


--
-- TOC entry 4304 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."NetPremium"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."NetPremium" IS 'เบี้ยสุทธิ';


--
-- TOC entry 4305 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."ConfirmDate"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."ConfirmDate" IS 'วันที่แจ้งประกัน';


--
-- TOC entry 4306 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."Discount"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."Discount" IS 'ส่วนลดให้ลูกค้า';


--
-- TOC entry 4307 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."CollectCus"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."CollectCus" IS 'เบี้ยที่จะเก็บกับลูกค้าซึ่งหลังจากหักส่วนลดแล้ว';


--
-- TOC entry 4308 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."InsUser"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."InsUser" IS 'ผู้รับแจ้งของบริษัทประกัน';


--
-- TOC entry 4309 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."Remark"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."Remark" IS 'หมายเหตุต่างในการต่อรองกับลูกค้า';


--
-- TOC entry 4310 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."CusPayReady"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."CusPayReady" IS 'สถานะการชำระของลูกค้า  true = ชำระครบ  false = ยังไม่ครบ';


--
-- TOC entry 4311 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."Cancel"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."Cancel" IS 'สถานะการยกเลิกกรมธรรม์  true = ยกเลิก  false = ยังมีผลคุ้มครองอยู่';


--
-- TOC entry 4312 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."Commision"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."Commision" IS 'ค่านายหน้าที่บริษัทได้จากประกัน';


--
-- TOC entry 4313 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."CoPayInsAmt"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."CoPayInsAmt" IS 'ค่าเบี้ยที่บริัษัทจ่ายให้บริษัทประภันหลังหักค่านายหน้าแล้ว';


--
-- TOC entry 4314 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."CoPayInsReady"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."CoPayInsReady" IS 'สถานะที่บริษัทชำระค่าเบี้ยให้ประกัน true = ชำระแล้ว  false = ยังไม่ชำระ';


--
-- TOC entry 4315 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."CoPayInsID"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."CoPayInsID" IS 'เลขรับของการชำระเบี้ยให้บริษัทประกัน';


--
-- TOC entry 4316 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN "InsureUnforce"."EndDate"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce"."EndDate" IS 'วันที่หมดอายุในกรมธรรม์';


--
-- TOC entry 432 (class 1259 OID 68892)
-- Dependencies: 3425 3426 3427 3428 3429 3430 3431 3432 3433 3434 10
-- Name: InsureUnforce_Backup; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE "InsureUnforce_Backup" (
    "InsUFIDNO" character varying(12) NOT NULL,
    "IDNO" character varying(25),
    "CusID" character varying(12) NOT NULL,
    "CarID" character varying(12) NOT NULL,
    "InsID" character varying(25),
    "TempInsID" character varying(25),
    "Company" character varying(5) NOT NULL,
    "StartDate" date NOT NULL,
    "Code" character varying(5) NOT NULL,
    "Kind" character varying(2) NOT NULL,
    "Invest" integer DEFAULT 0 NOT NULL,
    "Premium" double precision DEFAULT 0 NOT NULL,
    "NetPremium" double precision DEFAULT 0 NOT NULL,
    "ConfirmDate" date NOT NULL,
    "Discount" real DEFAULT 0 NOT NULL,
    "CollectCus" double precision DEFAULT 0 NOT NULL,
    "InsUser" character varying(20),
    "Remark" text,
    "CusPayReady" boolean DEFAULT false NOT NULL,
    "Cancel" boolean DEFAULT false NOT NULL,
    "Commision" real DEFAULT 0 NOT NULL,
    "CoPayInsAmt" double precision DEFAULT 0 NOT NULL,
    "CoPayInsReady" boolean DEFAULT false NOT NULL,
    "CoPayInsID" character varying(12),
    "EndDate" date,
    "InsDate" date
);


ALTER TABLE insure."InsureUnforce_Backup" OWNER TO dev;

--
-- TOC entry 4317 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."InsUFIDNO"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."InsUFIDNO" IS 'เลขที่ภายในของบริษัท IF0812001';


--
-- TOC entry 4318 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."IDNO"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."IDNO" IS 'เลขที่สัญญาเช่าซื้อ จาก Fp';


--
-- TOC entry 4319 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."CusID"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."CusID" IS 'เลขที่ของลูกค้า จาก fa1';


--
-- TOC entry 4320 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."InsID"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."InsID" IS 'เลขที่ของกรมธรรม์';


--
-- TOC entry 4321 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."TempInsID"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."TempInsID" IS 'เลขรับแจ้งใช้ชั่วคราวขณะที่เลขกรมธรรม์ยังไม่ออก';


--
-- TOC entry 4322 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."Company"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."Company" IS 'รหัสบริษัทประกันภัย';


--
-- TOC entry 4323 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."StartDate"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."StartDate" IS 'วันที่เริ่มคุ้มครองในกรมธรรม์';


--
-- TOC entry 4324 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."Code"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."Code" IS 'รหัสประเภทของรถที่คุ้มครอง';


--
-- TOC entry 4325 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."Kind"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."Kind" IS 'ประเภทของแระภัน เช่น 1,2,3, T1,T2,T3';


--
-- TOC entry 4326 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."Invest"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."Invest" IS 'ทุนประภัน';


--
-- TOC entry 4327 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."Premium"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."Premium" IS 'เบี้ยประกันทั้งหมด';


--
-- TOC entry 4328 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."NetPremium"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."NetPremium" IS 'เบี้ยสุทธิ';


--
-- TOC entry 4329 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."ConfirmDate"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."ConfirmDate" IS 'วันที่แจ้งประกัน';


--
-- TOC entry 4330 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."Discount"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."Discount" IS 'ส่วนลดให้ลูกค้า';


--
-- TOC entry 4331 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."CollectCus"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."CollectCus" IS 'เบี้ยที่จะเก็บกับลูกค้าซึ่งหลังจากหักส่วนลดแล้ว';


--
-- TOC entry 4332 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."InsUser"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."InsUser" IS 'ผู้รับแจ้งของบริษัทประกัน';


--
-- TOC entry 4333 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."Remark"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."Remark" IS 'หมายเหตุต่างในการต่อรองกับลูกค้า';


--
-- TOC entry 4334 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."CusPayReady"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."CusPayReady" IS 'สถานะการชำระของลูกค้า  true = ชำระครบ  false = ยังไม่ครบ';


--
-- TOC entry 4335 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."Cancel"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."Cancel" IS 'สถานะการยกเลิกกรมธรรม์  true = ยกเลิก  false = ยังมีผลคุ้มครองอยู่';


--
-- TOC entry 4336 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."Commision"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."Commision" IS 'ค่านายหน้าที่บริษัทได้จากประกัน';


--
-- TOC entry 4337 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."CoPayInsAmt"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."CoPayInsAmt" IS 'ค่าเบี้ยที่บริัษัทจ่ายให้บริษัทประภันหลังหักค่านายหน้าแล้ว';


--
-- TOC entry 4338 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."CoPayInsReady"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."CoPayInsReady" IS 'สถานะที่บริษัทชำระค่าเบี้ยให้ประกัน true = ชำระแล้ว  false = ยังไม่ชำระ';


--
-- TOC entry 4339 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."CoPayInsID"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."CoPayInsID" IS 'เลขรับของการชำระเบี้ยให้บริษัทประกัน';


--
-- TOC entry 4340 (class 0 OID 0)
-- Dependencies: 432
-- Name: COLUMN "InsureUnforce_Backup"."EndDate"; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "InsureUnforce_Backup"."EndDate" IS 'วันที่หมดอายุในกรมธรรม์';


--
-- TOC entry 238 (class 1259 OID 64894)
-- Dependencies: 3245 3246 3247 10
-- Name: PayToInsure; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE "PayToInsure" (
    "PayID" character varying(12) NOT NULL,
    "DoDate" date NOT NULL,
    "Cash" double precision DEFAULT 0 NOT NULL,
    "CQBank" character varying(15),
    "CQID" character varying(15),
    "CQDate" date,
    "CQAmt" double precision DEFAULT 0,
    "Cancel" boolean DEFAULT false NOT NULL,
    "Remark" text,
    idmaker character varying(10),
    idauthority character varying(10),
    kind smallint,
    "Company" character varying(10)
);


ALTER TABLE insure."PayToInsure" OWNER TO dev;

--
-- TOC entry 4341 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN "PayToInsure".kind; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN "PayToInsure".kind IS '1=force ,  2 = unforce';


--
-- TOC entry 239 (class 1259 OID 64903)
-- Dependencies: 10
-- Name: RateInsForce; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE "RateInsForce" (
    "IFCode" character varying(5) NOT NULL,
    "IFNetPremium" real,
    "IFTAX" real,
    "IFStamp" real,
    "IFDetail" character varying(30),
    "BodyType" character varying(50),
    "UseRent" boolean,
    "CapacityUnit" character varying(100)
);


ALTER TABLE insure."RateInsForce" OWNER TO dev;

SET search_path = public, pg_catalog;

--
-- TOC entry 240 (class 1259 OID 64906)
-- Dependencies: 3248 15
-- Name: Fa1; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fa1" (
    "CusID" character(12) NOT NULL,
    "A_FIRNAME" character(20),
    "A_NAME" character(60),
    "A_SIRNAME" character(60),
    "A_PAIR" character(120),
    "A_NO" character(15),
    "A_SUBNO" character(10),
    "A_SOI" character(60),
    "A_RD" character(60),
    "A_TUM" character(60),
    "A_AUM" character(60),
    "A_PRO" character(60),
    "A_POST" character varying(10),
    "Approved" boolean DEFAULT false NOT NULL
);


ALTER TABLE public."Fa1" OWNER TO dev;

--
-- TOC entry 241 (class 1259 OID 64913)
-- Dependencies: 3249 3250 3251 3252 15
-- Name: Fc; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fc" (
    "CarID" character varying(12) NOT NULL,
    "C_CARNAME" character varying(60),
    "C_YEAR" character varying(6),
    "C_REGIS" character varying(10),
    "C_REGIS_BY" character varying(15) DEFAULT 'กรุงเทพ'::character varying,
    "C_COLOR" character varying(15) DEFAULT 'เหลือง'::character varying,
    "C_CARNUM" character varying(22),
    "C_MARNUM" character varying(20),
    "C_Milage" character varying(8) DEFAULT 0,
    "C_TAX_ExpDate" date,
    "C_TAX_MON" double precision DEFAULT 0,
    "C_StartDate" date,
    "RadioID" character varying(10),
    "CarType" smallint,
    "C_CAR_CC" double precision
);


ALTER TABLE public."Fc" OWNER TO dev;

--
-- TOC entry 4342 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN "Fc"."C_StartDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fc"."C_StartDate" IS 'วันจดทะเบียน';


--
-- TOC entry 4343 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN "Fc"."RadioID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fc"."RadioID" IS 'เลขวิทยุ หรือ โทรศัพท์ที่ลูกค้าใช้ในรถ';


--
-- TOC entry 4344 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN "Fc"."CarType"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fc"."CarType" IS 'รูปแบบรถยนต์
0 =  รถนั่งทั่วไป
1 = แท็กซี่บริษัท
2 = แท็กซี่เขียวเหลือง
3 = แท็กซี่สีอื่นๆ';


--
-- TOC entry 4345 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN "Fc"."C_CAR_CC"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fc"."C_CAR_CC" IS 'cc รถ';


SET search_path = insure, pg_catalog;

--
-- TOC entry 242 (class 1259 OID 64920)
-- Dependencies: 3085 10
-- Name: VInsForceDetail; Type: VIEW; Schema: insure; Owner: dev
--

CREATE VIEW "VInsForceDetail" AS
    SELECT "InsureForce"."InsFIDNO", "InsureForce"."Company", "InsureForce"."InsID", "InsureForce"."IDNO", "Fa1"."CusID", ((((btrim(("Fa1"."A_FIRNAME")::text) || ' '::text) || btrim(("Fa1"."A_NAME")::text)) || ' '::text) || btrim(("Fa1"."A_SIRNAME")::text)) AS full_name, "Fc"."CarID" AS asset_id, "Fc"."C_CARNAME", "Fc"."C_REGIS", "Fc"."C_COLOR", "Fc"."C_CARNUM", "InsureForce"."StartDate", "InsureForce"."EndDate", "InsureForce"."NetPremium", "InsureForce"."Premium", outstanding_insforce(("InsureForce"."InsFIDNO")::text) AS outstanding FROM "InsureForce", public."Fa1", public."Fc" WHERE (((("InsureForce"."CusID")::bpchar = "Fa1"."CusID") AND (("InsureForce"."CarID")::text = ("Fc"."CarID")::text)) AND ("InsureForce"."Cancel" = false));


ALTER TABLE insure."VInsForceDetail" OWNER TO dev;

--
-- TOC entry 243 (class 1259 OID 64925)
-- Dependencies: 3086 10
-- Name: VInsUnforceDetail; Type: VIEW; Schema: insure; Owner: dev
--

CREATE VIEW "VInsUnforceDetail" AS
    SELECT "InsureUnforce"."InsUFIDNO", "InsureUnforce"."Company", "InsureUnforce"."TempInsID", "InsureUnforce"."InsID", "InsureUnforce"."IDNO", "Fa1"."CusID", ((((btrim(("Fa1"."A_FIRNAME")::text) || ' '::text) || btrim(("Fa1"."A_NAME")::text)) || ' '::text) || btrim(("Fa1"."A_SIRNAME")::text)) AS full_name, "Fc"."CarID" AS asset_id, "Fc"."C_CARNAME", "Fc"."C_REGIS", "Fc"."C_COLOR", "Fc"."C_CARNUM", "InsureUnforce"."StartDate", "InsureUnforce"."NetPremium", "InsureUnforce"."Premium", outstanding_insureunforce(("InsureUnforce"."InsUFIDNO")::text) AS outstanding FROM "InsureUnforce" "InsureUnforce", public."Fa1", public."Fc" WHERE (((("InsureUnforce"."CusID")::bpchar = "Fa1"."CusID") AND (("InsureUnforce"."CarID")::text = ("Fc"."CarID")::text)) AND ("InsureUnforce"."Cancel" = false));


ALTER TABLE insure."VInsUnforceDetail" OWNER TO dev;

--
-- TOC entry 244 (class 1259 OID 64930)
-- Dependencies: 3087 10
-- Name: VListForcePayBy; Type: VIEW; Schema: insure; Owner: dev
--

CREATE VIEW "VListForcePayBy" AS
    SELECT f."InsFIDNO", f."IDNO", f."CusID", f."CarID", f."InsID", f."Company", f."StartDate", f."Premium", f."NetPremium", f."Discount", f."CollectCus", f."CusPayReady", f."Cancel", f."Commision", f."CoPayInsAmt", f."EndDate", p."PayID", p."DoDate", p."Cash", p."CQBank", p."CQID", p."CQDate", p."CQAmt", p."Remark", p.idmaker, p.idauthority FROM "InsureForce" f, "PayToInsure" p WHERE (((f."CoPayInsID")::text = (p."PayID")::text) AND ((p."CQBank" IS NOT NULL) OR (p."Cash" <> (0)::double precision))) ORDER BY f."Company", f."CoPayInsID";


ALTER TABLE insure."VListForcePayBy" OWNER TO dev;

--
-- TOC entry 245 (class 1259 OID 64935)
-- Dependencies: 3088 10
-- Name: VListUnForcePayBy; Type: VIEW; Schema: insure; Owner: dev
--

CREATE VIEW "VListUnForcePayBy" AS
    SELECT u."InsUFIDNO", u."IDNO", u."CusID", u."CarID", u."InsID", u."TempInsID", u."Company", u."StartDate", u."Kind", u."Premium", u."NetPremium", u."ConfirmDate", u."Discount", u."CollectCus", u."CusPayReady", u."Cancel", u."Commision", u."CoPayInsAmt", u."EndDate", p."PayID", p."DoDate", p."Cash", p."CQBank", p."CQID", p."CQDate", p."CQAmt", p."Remark", p.idmaker, p.idauthority FROM "InsureUnforce" u, "PayToInsure" p WHERE (((u."CoPayInsID")::text = (p."PayID")::text) AND ((p."CQBank" IS NOT NULL) OR (p."Cash" <> (0)::double precision))) ORDER BY u."Company", u."CoPayInsID";


ALTER TABLE insure."VListUnForcePayBy" OWNER TO dev;

--
-- TOC entry 246 (class 1259 OID 64940)
-- Dependencies: 10
-- Name: batch; Type: TABLE; Schema: insure; Owner: dev; Tablespace: 
--

CREATE TABLE batch (
    auto_id integer NOT NULL,
    id character varying(12) NOT NULL,
    do_date date NOT NULL,
    marker_id character varying(10) NOT NULL,
    approve_date date,
    approve_id character varying(10),
    type character(1) NOT NULL,
    "InsID" character varying(25),
    "Company" character varying(5),
    "StartDate" date,
    "EndDate" date,
    "Code" character varying(5),
    "Kind" character varying(2),
    "Invest" double precision,
    "Premium" double precision,
    "Discount" double precision,
    "CollectCus" double precision,
    "InsUser" character varying(20),
    "InsMark" character varying(20),
    "Capacity" character varying(5),
    "NetPremium" double precision,
    "TaxStamp" double precision,
    "Vat" double precision
);


ALTER TABLE insure.batch OWNER TO dev;

--
-- TOC entry 4346 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN batch.type; Type: COMMENT; Schema: insure; Owner: dev
--

COMMENT ON COLUMN batch.type IS 'O = ข้อมูลเก่า
N = ข้อมูลใหม่';


--
-- TOC entry 247 (class 1259 OID 64943)
-- Dependencies: 246 10
-- Name: batch_auto_id_seq; Type: SEQUENCE; Schema: insure; Owner: dev
--

CREATE SEQUENCE batch_auto_id_seq
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE insure.batch_auto_id_seq OWNER TO dev;

--
-- TOC entry 4347 (class 0 OID 0)
-- Dependencies: 247
-- Name: batch_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: insure; Owner: dev
--

ALTER SEQUENCE batch_auto_id_seq OWNED BY batch.auto_id;


SET search_path = letter, pg_catalog;

--
-- TOC entry 248 (class 1259 OID 64945)
-- Dependencies: 3254 11
-- Name: SendDetail; Type: TABLE; Schema: letter; Owner: dev; Tablespace: 
--

CREATE TABLE "SendDetail" (
    auto_id integer NOT NULL,
    send_date date,
    "IDNO" character varying(25),
    address_id integer,
    detail character varying(50),
    userid character varying(12),
    type_send character(1) DEFAULT 'N'::bpchar,
    receive_date date,
    coname character varying(100)
);


ALTER TABLE letter."SendDetail" OWNER TO dev;

--
-- TOC entry 4348 (class 0 OID 0)
-- Dependencies: 248
-- Name: COLUMN "SendDetail".detail; Type: COMMENT; Schema: letter; Owner: dev
--

COMMENT ON COLUMN "SendDetail".detail IS 'เป็นตัวเลขจาก table type_letter แปลงเป็น chr() แล้วเก็บต่อกัน ไม่เกิน 19 ชนิด 
มี format เป็น :  1,2,7,... ';


--
-- TOC entry 4349 (class 0 OID 0)
-- Dependencies: 248
-- Name: COLUMN "SendDetail".type_send; Type: COMMENT; Schema: letter; Owner: dev
--

COMMENT ON COLUMN "SendDetail".type_send IS 'N= ส่งธรรมดา
R= ลงทะเบียน
A= ลงทะเบียนตอบรับ';


--
-- TOC entry 4350 (class 0 OID 0)
-- Dependencies: 248
-- Name: COLUMN "SendDetail".receive_date; Type: COMMENT; Schema: letter; Owner: dev
--

COMMENT ON COLUMN "SendDetail".receive_date IS 'วันที่ได้รับจดหมายตอบรับ';


--
-- TOC entry 4351 (class 0 OID 0)
-- Dependencies: 248
-- Name: COLUMN "SendDetail".coname; Type: COMMENT; Schema: letter; Owner: dev
--

COMMENT ON COLUMN "SendDetail".coname IS 'ชื่อผู้เข้าร่วม';


--
-- TOC entry 249 (class 1259 OID 64949)
-- Dependencies: 248 11
-- Name: SendDetail_auto_id_seq; Type: SEQUENCE; Schema: letter; Owner: dev
--

CREATE SEQUENCE "SendDetail_auto_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE letter."SendDetail_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4352 (class 0 OID 0)
-- Dependencies: 249
-- Name: SendDetail_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: letter; Owner: dev
--

ALTER SEQUENCE "SendDetail_auto_id_seq" OWNED BY "SendDetail".auto_id;


--
-- TOC entry 250 (class 1259 OID 64951)
-- Dependencies: 3256 11
-- Name: cus_address; Type: TABLE; Schema: letter; Owner: dev; Tablespace: 
--

CREATE TABLE cus_address (
    address_id integer NOT NULL,
    "CusID" character varying(12),
    change_date date,
    address text,
    "Active" boolean DEFAULT true,
    user_id character varying(12)
);


ALTER TABLE letter.cus_address OWNER TO dev;

SET search_path = public, pg_catalog;

--
-- TOC entry 251 (class 1259 OID 64958)
-- Dependencies: 3258 15
-- Name: ContactCus; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "ContactCus" (
    "IDNO" character varying(25) NOT NULL,
    "CusState" smallint DEFAULT 0 NOT NULL,
    "CusID" character varying(12) NOT NULL
);


ALTER TABLE public."ContactCus" OWNER TO dev;

--
-- TOC entry 4353 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN "ContactCus"."CusState"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "ContactCus"."CusState" IS '0=ผ/ช . 1..N = ผ/ค 1..N';


SET search_path = letter, pg_catalog;

--
-- TOC entry 252 (class 1259 OID 64962)
-- Dependencies: 3089 11
-- Name: VAddressList; Type: VIEW; Schema: letter; Owner: dev
--

CREATE VIEW "VAddressList" AS
    SELECT c."IDNO", c."CusState", c."CusID", a.address_id, a."Active", a.address FROM public."ContactCus" c, cus_address a WHERE (((c."CusID")::text = (a."CusID")::text) AND (a."Active" = true));


ALTER TABLE letter."VAddressList" OWNER TO dev;

--
-- TOC entry 253 (class 1259 OID 64966)
-- Dependencies: 250 11
-- Name: cus_address_address_id_seq; Type: SEQUENCE; Schema: letter; Owner: dev
--

CREATE SEQUENCE cus_address_address_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE letter.cus_address_address_id_seq OWNER TO dev;

--
-- TOC entry 4354 (class 0 OID 0)
-- Dependencies: 253
-- Name: cus_address_address_id_seq; Type: SEQUENCE OWNED BY; Schema: letter; Owner: dev
--

ALTER SEQUENCE cus_address_address_id_seq OWNED BY cus_address.address_id;


--
-- TOC entry 434 (class 1259 OID 68919)
-- Dependencies: 3436 11
-- Name: cus_address_backup; Type: TABLE; Schema: letter; Owner: dev; Tablespace: 
--

CREATE TABLE cus_address_backup (
    address_id integer NOT NULL,
    "CusID" character varying(12),
    change_date date,
    address text,
    "Active" boolean DEFAULT true,
    user_id character varying(12)
);


ALTER TABLE letter.cus_address_backup OWNER TO dev;

--
-- TOC entry 433 (class 1259 OID 68917)
-- Dependencies: 11 434
-- Name: cus_address_backup_address_id_seq; Type: SEQUENCE; Schema: letter; Owner: dev
--

CREATE SEQUENCE cus_address_backup_address_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE letter.cus_address_backup_address_id_seq OWNER TO dev;

--
-- TOC entry 4355 (class 0 OID 0)
-- Dependencies: 433
-- Name: cus_address_backup_address_id_seq; Type: SEQUENCE OWNED BY; Schema: letter; Owner: dev
--

ALTER SEQUENCE cus_address_backup_address_id_seq OWNED BY cus_address_backup.address_id;


--
-- TOC entry 254 (class 1259 OID 64968)
-- Dependencies: 11
-- Name: noadd_letter_auto_id_seq; Type: SEQUENCE; Schema: letter; Owner: dev
--

CREATE SEQUENCE noadd_letter_auto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE letter.noadd_letter_auto_id_seq OWNER TO dev;

--
-- TOC entry 255 (class 1259 OID 64970)
-- Dependencies: 3259 11
-- Name: dontsave_address; Type: TABLE; Schema: letter; Owner: dev; Tablespace: 
--

CREATE TABLE dontsave_address (
    "dontID" integer DEFAULT nextval('noadd_letter_auto_id_seq'::regclass) NOT NULL,
    auto_id integer
);


ALTER TABLE letter.dontsave_address OWNER TO dev;

--
-- TOC entry 256 (class 1259 OID 64974)
-- Dependencies: 11
-- Name: regis_sent_reg_id_seq; Type: SEQUENCE; Schema: letter; Owner: dev
--

CREATE SEQUENCE regis_sent_reg_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE letter.regis_sent_reg_id_seq OWNER TO dev;

--
-- TOC entry 257 (class 1259 OID 64976)
-- Dependencies: 3260 11
-- Name: regis_send; Type: TABLE; Schema: letter; Owner: dev; Tablespace: 
--

CREATE TABLE regis_send (
    reg_id integer DEFAULT nextval('regis_sent_reg_id_seq'::regclass) NOT NULL,
    reg_num character varying(50),
    auto_id integer
);


ALTER TABLE letter.regis_send OWNER TO dev;

--
-- TOC entry 258 (class 1259 OID 64980)
-- Dependencies: 3261 11
-- Name: send_address; Type: TABLE; Schema: letter; Owner: dev; Tablespace: 
--

CREATE TABLE send_address (
    "CusLetID" character varying(25) NOT NULL,
    "IDNO" character varying(25) NOT NULL,
    record_date date NOT NULL,
    name character varying(120) NOT NULL,
    active boolean DEFAULT true NOT NULL,
    userid character varying(12) NOT NULL,
    dtl_ads text,
    "CusState" smallint
);


ALTER TABLE letter.send_address OWNER TO dev;

--
-- TOC entry 4356 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN send_address.record_date; Type: COMMENT; Schema: letter; Owner: dev
--

COMMENT ON COLUMN send_address.record_date IS 'วันที่บันทึกที่อยู่';


--
-- TOC entry 4357 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN send_address.active; Type: COMMENT; Schema: letter; Owner: dev
--

COMMENT ON COLUMN send_address.active IS 'สถานะที่ของrecordนี้  true = ยังใช่อยู่,    false = ไม่ใช่แล้ว';


--
-- TOC entry 259 (class 1259 OID 64987)
-- Dependencies: 11
-- Name: send_detail; Type: TABLE; Schema: letter; Owner: dev; Tablespace: 
--

CREATE TABLE send_detail (
    send_date date NOT NULL,
    "sendID" character varying(12) NOT NULL,
    "CusLetID" character varying(25),
    detail text NOT NULL,
    userid character varying(12) NOT NULL
);


ALTER TABLE letter.send_detail OWNER TO dev;

--
-- TOC entry 4358 (class 0 OID 0)
-- Dependencies: 259
-- Name: COLUMN send_detail.detail; Type: COMMENT; Schema: letter; Owner: dev
--

COMMENT ON COLUMN send_detail.detail IS 'array of type_letter  เช่น 1/2/3  = ใบเสร็จ . ภาษี . ประกัน ';


--
-- TOC entry 260 (class 1259 OID 64993)
-- Dependencies: 11
-- Name: send_no; Type: TABLE; Schema: letter; Owner: dev; Tablespace: 
--

CREATE TABLE send_no (
    send_date date NOT NULL,
    s_no smallint NOT NULL
);


ALTER TABLE letter.send_no OWNER TO dev;

--
-- TOC entry 261 (class 1259 OID 64996)
-- Dependencies: 3262 11
-- Name: type_letter; Type: TABLE; Schema: letter; Owner: dev; Tablespace: 
--

CREATE TABLE type_letter (
    auto_id integer NOT NULL,
    type_name character varying(100) NOT NULL,
    is_use boolean DEFAULT true NOT NULL
);


ALTER TABLE letter.type_letter OWNER TO dev;

--
-- TOC entry 262 (class 1259 OID 65000)
-- Dependencies: 11 261
-- Name: type_letter_auto_id_seq; Type: SEQUENCE; Schema: letter; Owner: dev
--

CREATE SEQUENCE type_letter_auto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE letter.type_letter_auto_id_seq OWNER TO dev;

--
-- TOC entry 4359 (class 0 OID 0)
-- Dependencies: 262
-- Name: type_letter_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: letter; Owner: dev
--

ALTER SEQUENCE type_letter_auto_id_seq OWNED BY type_letter.auto_id;


SET search_path = pmain, pg_catalog;

--
-- TOC entry 263 (class 1259 OID 65002)
-- Dependencies: 12
-- Name: fletter; Type: TABLE; Schema: pmain; Owner: dev; Tablespace: 
--

CREATE TABLE fletter (
    "IDNO" character varying(25) NOT NULL,
    "INCREMENTAL" integer NOT NULL,
    "NAME" character varying(40),
    "ADDRESS1" character varying(40),
    "ADDRESS2" character varying(40),
    "ADDRESS3" character varying(40),
    "CONTRACT" boolean,
    "RECEIPT" boolean,
    "PRB" boolean,
    "CAR_TAX" boolean,
    "INSURANCE" boolean,
    "TITLE" boolean,
    "NOTICE" boolean,
    "InsNotice" boolean,
    "KeptSentAddRec" boolean,
    "SENDDATE" date
);


ALTER TABLE pmain.fletter OWNER TO dev;

--
-- TOC entry 264 (class 1259 OID 65005)
-- Dependencies: 12 263
-- Name: fletter_INCREMENTAL_seq; Type: SEQUENCE; Schema: pmain; Owner: dev
--

CREATE SEQUENCE "fletter_INCREMENTAL_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pmain."fletter_INCREMENTAL_seq" OWNER TO dev;

--
-- TOC entry 4360 (class 0 OID 0)
-- Dependencies: 264
-- Name: fletter_INCREMENTAL_seq; Type: SEQUENCE OWNED BY; Schema: pmain; Owner: dev
--

ALTER SEQUENCE "fletter_INCREMENTAL_seq" OWNED BY fletter."INCREMENTAL";


--
-- TOC entry 265 (class 1259 OID 65007)
-- Dependencies: 12
-- Name: new_fp_trans; Type: TABLE; Schema: pmain; Owner: dev; Tablespace: 
--

CREATE TABLE new_fp_trans (
    "IDNO" character varying(25) NOT NULL,
    "TranIDRef1" character varying(15),
    "TranIDRef2" character varying(15)
);


ALTER TABLE pmain.new_fp_trans OWNER TO dev;

--
-- TOC entry 4361 (class 0 OID 0)
-- Dependencies: 265
-- Name: TABLE new_fp_trans; Type: COMMENT; Schema: pmain; Owner: dev
--

COMMENT ON TABLE new_fp_trans IS 'Table Fp จากระบบเก่า โดยมีข้อมูลเฉพาะ IDNO, Trans1, Trans2 สำหรับเช็คกรณีเสียรหัส Bill Payment ไปเนื่องจากการ Up ข้อมูลใหม่';


--
-- TOC entry 4362 (class 0 OID 0)
-- Dependencies: 265
-- Name: COLUMN new_fp_trans."IDNO"; Type: COMMENT; Schema: pmain; Owner: dev
--

COMMENT ON COLUMN new_fp_trans."IDNO" IS 'เลขที่สัญญาเช่าซื้อที่มีในระบบเก่า จับคู่กับ TranIDRef1, TranIDRef2';


--
-- TOC entry 4363 (class 0 OID 0)
-- Dependencies: 265
-- Name: COLUMN new_fp_trans."TranIDRef1"; Type: COMMENT; Schema: pmain; Owner: dev
--

COMMENT ON COLUMN new_fp_trans."TranIDRef1" IS 'TranIDRef1 ของระบบเก่าซึ่ง Gen คนละแบบจากระบบใหม่';


--
-- TOC entry 4364 (class 0 OID 0)
-- Dependencies: 265
-- Name: COLUMN new_fp_trans."TranIDRef2"; Type: COMMENT; Schema: pmain; Owner: dev
--

COMMENT ON COLUMN new_fp_trans."TranIDRef2" IS 'TranIDRef2 ของระบบเก่าซึ่ง Gen คนละแบบจากระบบใหม่';


SET search_path = public, pg_catalog;

--
-- TOC entry 266 (class 1259 OID 65010)
-- Dependencies: 15
-- Name: AccPayment; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "AccPayment" (
    "IDNO" character varying(25) NOT NULL,
    "DueNo" smallint NOT NULL,
    "DueDate" date,
    "Remine" double precision,
    "Priciple" double precision,
    "Interest" double precision,
    "AccuInt" double precision,
    "WaitIncome" double precision,
    "CrDebt" double precision,
    "Commis" double precision,
    "CommPart" double precision,
    "CPAcuu" double precision,
    "CommInterest" double precision,
    "CommAccuInt" double precision,
    "CommCrDebt" double precision,
    "CommWaitIncom" double precision,
    "CommPriciple" double precision
);


ALTER TABLE public."AccPayment" OWNER TO dev;

--
-- TOC entry 267 (class 1259 OID 65013)
-- Dependencies: 15
-- Name: BankCheque; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "BankCheque" (
    "BankCode" character varying(15) NOT NULL,
    "BankName" character varying(50) NOT NULL,
    "BankNo" character varying(10)
);


ALTER TABLE public."BankCheque" OWNER TO dev;

--
-- TOC entry 268 (class 1259 OID 65016)
-- Dependencies: 15
-- Name: BankInt; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "BankInt" (
    "BAccount" character varying(20) NOT NULL,
    "BName" character varying(50) NOT NULL,
    "BBranch" character varying(200) NOT NULL,
    "BCompany" character varying(10) NOT NULL,
    "BType" smallint NOT NULL,
    "BActive" smallint NOT NULL,
    "BSort" smallint NOT NULL
);


ALTER TABLE public."BankInt" OWNER TO dev;

--
-- TOC entry 4365 (class 0 OID 0)
-- Dependencies: 268
-- Name: TABLE "BankInt"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "BankInt" IS 'รายละเอียดบัญชีธนาคารของบริษัท';


--
-- TOC entry 4366 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN "BankInt"."BAccount"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "BankInt"."BAccount" IS 'เลขที่บัญชีธนาคาร';


--
-- TOC entry 4367 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN "BankInt"."BName"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "BankInt"."BName" IS 'ชื่อธนาคาร';


--
-- TOC entry 4368 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN "BankInt"."BBranch"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "BankInt"."BBranch" IS 'สาขาธนาคาร';


--
-- TOC entry 4369 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN "BankInt"."BCompany"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "BankInt"."BCompany" IS 'เจ้าของบัญชีธนาคาร';


--
-- TOC entry 4370 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN "BankInt"."BType"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "BankInt"."BType" IS 'ประเภทบัญชี 1 - กระแสรายวัน / 2 - ออมทรัพย์';


--
-- TOC entry 4371 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN "BankInt"."BActive"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "BankInt"."BActive" IS 'สถานะ';


--
-- TOC entry 269 (class 1259 OID 65019)
-- Dependencies: 15
-- Name: BankProfile; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "BankProfile" (
    "bankID" character varying(15) NOT NULL,
    "bankName" character varying(100),
    "bankSort" smallint
);


ALTER TABLE public."BankProfile" OWNER TO dev;

--
-- TOC entry 4372 (class 0 OID 0)
-- Dependencies: 269
-- Name: TABLE "BankProfile"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "BankProfile" IS 'รายละเอียดธนาคาร';


--
-- TOC entry 270 (class 1259 OID 65022)
-- Dependencies: 3265 3266 3267 3268 15
-- Name: CancelReceipt; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "CancelReceipt" (
    c_receipt character varying(10) NOT NULL,
    "IDNO" character varying(25) NOT NULL,
    c_date date NOT NULL,
    c_money double precision DEFAULT 0 NOT NULL,
    ref_prndate date NOT NULL,
    ref_recdate date NOT NULL,
    ref_receipt character varying(10) NOT NULL,
    paytypefrom character varying(10) DEFAULT 'OC'::character varying NOT NULL,
    return_to character varying(12) NOT NULL,
    admin_approve boolean DEFAULT false,
    c_memo text,
    postuser character varying(10),
    approveuser character varying(10),
    "statusApprove" boolean DEFAULT true
);


ALTER TABLE public."CancelReceipt" OWNER TO dev;

--
-- TOC entry 4373 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN "CancelReceipt".paytypefrom; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "CancelReceipt".paytypefrom IS 'OC , TMB , SCB, KTB';


--
-- TOC entry 4374 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN "CancelReceipt".return_to; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "CancelReceipt".return_to IS 'การคืนเงินรายการนี้ไปที่  ลูกค้าเป็นเงินสด เลขที่ใบรับเงินของลูกค้า   หรือ เข้าเงินรับฝากแลขที่ ';


--
-- TOC entry 4375 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN "CancelReceipt"."statusApprove"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "CancelReceipt"."statusApprove" IS 'เก็บสถานะการอนุมัติ
t=อนุมัติ
f=ไม่อนุมัติ';


--
-- TOC entry 271 (class 1259 OID 65032)
-- Dependencies: 3269 3270 15
-- Name: ContactCashID; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "ContactCashID" (
    c_year smallint DEFAULT 0 NOT NULL,
    c_branch smallint DEFAULT 0 NOT NULL,
    car_id smallint NOT NULL,
    gas_id smallint NOT NULL,
    tran_id smallint
);


ALTER TABLE public."ContactCashID" OWNER TO dev;

--
-- TOC entry 4376 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "ContactCashID".c_year; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "ContactCashID".c_year IS 'ปีที่ขายสด';


--
-- TOC entry 4377 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "ContactCashID".tran_id; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "ContactCashID".tran_id IS 'เลขที่สัญญาที่โอนสิทธิ์';


--
-- TOC entry 420 (class 1259 OID 68778)
-- Dependencies: 3402 15
-- Name: ContactCus_Backup; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "ContactCus_Backup" (
    "IDNO" character varying(25) NOT NULL,
    "CusState" smallint DEFAULT 0 NOT NULL,
    "CusID" character varying(12) NOT NULL
);


ALTER TABLE public."ContactCus_Backup" OWNER TO dev;

--
-- TOC entry 4378 (class 0 OID 0)
-- Dependencies: 420
-- Name: COLUMN "ContactCus_Backup"."CusState"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "ContactCus_Backup"."CusState" IS '0=ผ/ช . 1..N = ผ/ค 1..N';


--
-- TOC entry 272 (class 1259 OID 65037)
-- Dependencies: 3271 3272 3273 3274 15
-- Name: ContactID; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "ContactID" (
    branch smallint NOT NULL,
    monthid date NOT NULL,
    carid smallint DEFAULT 0 NOT NULL,
    gasid smallint DEFAULT 0 NOT NULL,
    ntid smallint DEFAULT 0,
    outcusid smallint DEFAULT 0
);


ALTER TABLE public."ContactID" OWNER TO dev;

--
-- TOC entry 4379 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN "ContactID".carid; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "ContactID".carid IS 'เลขที่สัญญาของรถยนต์';


--
-- TOC entry 4380 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN "ContactID".gasid; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "ContactID".gasid IS 'เลขที่สัญญาของแก๊ส';


--
-- TOC entry 4381 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN "ContactID".ntid; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "ContactID".ntid IS 'เลขที่หนังสือทวงหนี้ (NT)';


--
-- TOC entry 273 (class 1259 OID 65044)
-- Dependencies: 15
-- Name: CusPayment; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "CusPayment" (
    "IDNO" character varying(25) NOT NULL,
    "DueNo" smallint NOT NULL,
    "DueDate" date,
    "Remine" double precision,
    "Priciple" double precision,
    "Interest" double precision,
    "AccuInt" double precision,
    "WaitIncome" double precision,
    "CrDebt" double precision,
    "Commis" double precision,
    "CommPart" double precision,
    "CPAcuu" double precision,
    "CommInterest" double precision,
    "CommAccuInt" double precision,
    "CommCrDebt" double precision,
    "CommWaitIncom" double precision,
    "CommPriciple" double precision
);


ALTER TABLE public."CusPayment" OWNER TO dev;

--
-- TOC entry 274 (class 1259 OID 65047)
-- Dependencies: 15
-- Name: nw_customertemp_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE nw_customertemp_autoid_seq
    START WITH 13
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nw_customertemp_autoid_seq OWNER TO dev;

--
-- TOC entry 275 (class 1259 OID 65049)
-- Dependencies: 3275 3276 15
-- Name: Customer_Temp; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Customer_Temp" (
    "CustempID" smallint DEFAULT nextval('nw_customertemp_autoid_seq'::regclass) NOT NULL,
    "CusID" character varying(12),
    add_user character varying(12),
    add_date timestamp without time zone,
    app_user character varying(12),
    app_date timestamp without time zone,
    statusapp smallint,
    edittime integer,
    "A_FIRNAME" character(20),
    "A_NAME" character(60),
    "A_SIRNAME" character(60),
    "A_PAIR" character(120),
    "A_NO" character(15),
    "A_SUBNO" character(10),
    "A_SOI" character(60),
    "A_RD" character(60),
    "A_TUM" character(60),
    "A_AUM" character(60),
    "A_PRO" character(60),
    "A_POST" character varying(10),
    "N_SAN" character varying(60),
    "N_AGE" smallint DEFAULT 0,
    "N_CARD" character varying(30),
    "N_IDCARD" character varying(20),
    "N_OT_DATE" date,
    "N_BY" character varying(60),
    "N_OCC" character(60),
    "N_ContactAdd" text,
    "N_STATE" smallint NOT NULL
);


ALTER TABLE public."Customer_Temp" OWNER TO dev;

--
-- TOC entry 4382 (class 0 OID 0)
-- Dependencies: 275
-- Name: TABLE "Customer_Temp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "Customer_Temp" IS 'ตารางเก็บข้อมูลลูกค้าก่อนการอนุมัติเพิ่มหรือแก้ไข';


--
-- TOC entry 421 (class 1259 OID 68785)
-- Dependencies: 3403 3404 15
-- Name: Customer_Temp_Backup; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Customer_Temp_Backup" (
    "CustempID" smallint DEFAULT nextval('nw_customertemp_autoid_seq'::regclass) NOT NULL,
    "CusID" character varying(12),
    add_user character varying(12),
    add_date timestamp without time zone,
    app_user character varying(12),
    app_date timestamp without time zone,
    statusapp smallint,
    edittime integer,
    "A_FIRNAME" character(20),
    "A_NAME" character(60),
    "A_SIRNAME" character(60),
    "A_PAIR" character(120),
    "A_NO" character(15),
    "A_SUBNO" character(10),
    "A_SOI" character(60),
    "A_RD" character(60),
    "A_TUM" character(60),
    "A_AUM" character(60),
    "A_PRO" character(60),
    "A_POST" character varying(10),
    "N_SAN" character varying(60),
    "N_AGE" smallint DEFAULT 0,
    "N_CARD" character varying(30),
    "N_IDCARD" character varying(20),
    "N_OT_DATE" date,
    "N_BY" character varying(60),
    "N_OCC" character(60),
    "N_ContactAdd" text,
    "N_STATE" smallint NOT NULL
);


ALTER TABLE public."Customer_Temp_Backup" OWNER TO dev;

--
-- TOC entry 4383 (class 0 OID 0)
-- Dependencies: 421
-- Name: TABLE "Customer_Temp_Backup"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "Customer_Temp_Backup" IS 'ตารางเก็บข้อมูลลูกค้าก่อนการอนุมัติเพิ่มหรือแก้ไข';


--
-- TOC entry 276 (class 1259 OID 65057)
-- Dependencies: 15
-- Name: DTACCheque; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "DTACCheque" (
    "D_ChequeNo" character varying(15) NOT NULL,
    "D_BankID" character varying(15) NOT NULL,
    "D_BankBranch" character varying(15),
    "D_DateReceive" date,
    "D_DateOnChq" date,
    "D_DateEntBank" date,
    "BAccount" character varying(20),
    "D_Amount" numeric(15,2),
    "D_TACAmount" numeric(15,2),
    "D_RecNo" character varying(20),
    "doerID" character varying(12),
    "doerStamp" timestamp without time zone,
    "appvID" character varying(12),
    "appvStamp" timestamp without time zone,
    "auditorID" character varying(12),
    "auditorStamp" timestamp without time zone,
    status smallint
);


ALTER TABLE public."DTACCheque" OWNER TO dev;

--
-- TOC entry 277 (class 1259 OID 65060)
-- Dependencies: 15
-- Name: DetailCheque; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "DetailCheque" (
    auto_id integer NOT NULL,
    "PostID" character varying(12) NOT NULL,
    "ChequeNo" character varying(10) NOT NULL,
    "CusID" character varying(12) NOT NULL,
    "IDNO" character varying(25),
    "TypePay" smallint NOT NULL,
    "CusAmount" double precision,
    "ReceiptNo" character varying(12),
    "PrnDate" date
);


ALTER TABLE public."DetailCheque" OWNER TO dev;

--
-- TOC entry 4384 (class 0 OID 0)
-- Dependencies: 277
-- Name: COLUMN "DetailCheque"."CusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "DetailCheque"."CusID" IS 'รหัสลูกค้าใน fa1';


--
-- TOC entry 4385 (class 0 OID 0)
-- Dependencies: 277
-- Name: COLUMN "DetailCheque"."IDNO"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "DetailCheque"."IDNO" IS 'ถ้าเป็น null แสดงว่าเป็นลูกค้านอก จะมีแต่ cusid';


--
-- TOC entry 423 (class 1259 OID 68797)
-- Dependencies: 15
-- Name: DetailCheque_Backup; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "DetailCheque_Backup" (
    auto_id integer NOT NULL,
    "PostID" character varying(12) NOT NULL,
    "ChequeNo" character varying(10) NOT NULL,
    "CusID" character varying(12) NOT NULL,
    "IDNO" character varying(25),
    "TypePay" smallint NOT NULL,
    "CusAmount" double precision,
    "ReceiptNo" character varying(12),
    "PrnDate" date
);


ALTER TABLE public."DetailCheque_Backup" OWNER TO dev;

--
-- TOC entry 4386 (class 0 OID 0)
-- Dependencies: 423
-- Name: COLUMN "DetailCheque_Backup"."CusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "DetailCheque_Backup"."CusID" IS 'รหัสลูกค้าใน fa1';


--
-- TOC entry 4387 (class 0 OID 0)
-- Dependencies: 423
-- Name: COLUMN "DetailCheque_Backup"."IDNO"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "DetailCheque_Backup"."IDNO" IS 'ถ้าเป็น null แสดงว่าเป็นลูกค้านอก จะมีแต่ cusid';


--
-- TOC entry 422 (class 1259 OID 68795)
-- Dependencies: 423 15
-- Name: DetailCheque_Backup_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "DetailCheque_Backup_auto_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."DetailCheque_Backup_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4388 (class 0 OID 0)
-- Dependencies: 422
-- Name: DetailCheque_Backup_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "DetailCheque_Backup_auto_id_seq" OWNED BY "DetailCheque_Backup".auto_id;


--
-- TOC entry 278 (class 1259 OID 65063)
-- Dependencies: 277 15
-- Name: DetailCheque_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "DetailCheque_auto_id_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."DetailCheque_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4389 (class 0 OID 0)
-- Dependencies: 278
-- Name: DetailCheque_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "DetailCheque_auto_id_seq" OWNED BY "DetailCheque".auto_id;


--
-- TOC entry 279 (class 1259 OID 65065)
-- Dependencies: 3278 15
-- Name: DetailTranpay; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "DetailTranpay" (
    auto_id integer NOT NULL,
    "PostID" character varying(12) NOT NULL,
    "IDNO" character varying(25) NOT NULL,
    "TypePay" smallint NOT NULL,
    "Amount" double precision NOT NULL,
    "ReceiptNo" character varying(12),
    "PrnDate" date,
    "RefID" character varying(20),
    "Cancel" boolean DEFAULT false
);


ALTER TABLE public."DetailTranpay" OWNER TO dev;

--
-- TOC entry 280 (class 1259 OID 65069)
-- Dependencies: 279 15
-- Name: DetailTranpay_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "DetailTranpay_auto_id_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."DetailTranpay_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4390 (class 0 OID 0)
-- Dependencies: 280
-- Name: DetailTranpay_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "DetailTranpay_auto_id_seq" OWNED BY "DetailTranpay".auto_id;


--
-- TOC entry 281 (class 1259 OID 65071)
-- Dependencies: 15
-- Name: DocumentType; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "DocumentType" (
    id character varying(5) NOT NULL,
    name character varying(50),
    sub character varying(5),
    "table" character varying(100),
    fieldsearch character varying(100),
    fieldshow character varying(100),
    schema character varying(50)
);


ALTER TABLE public."DocumentType" OWNER TO dev;

--
-- TOC entry 282 (class 1259 OID 65074)
-- Dependencies: 3281 3282 3283 15
-- Name: FCash; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FCash" (
    auto_id integer NOT NULL,
    "PostID" character varying(12) NOT NULL,
    "CusID" character varying(12) NOT NULL,
    "IDNO" character varying(25) DEFAULT NULL::character varying,
    "TypePay" smallint NOT NULL,
    "AmtPay" double precision NOT NULL,
    refreceipt character varying(10) DEFAULT NULL::character varying,
    cancel boolean DEFAULT false
);


ALTER TABLE public."FCash" OWNER TO dev;

--
-- TOC entry 4391 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN "FCash"."CusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCash"."CusID" IS 'id ลูกค้าใน fa1';


--
-- TOC entry 4392 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN "FCash"."IDNO"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCash"."IDNO" IS 'ถ้าเป็น null แสดงว่าเป็นลูกค้านอกจะมีแต่ cusid';


--
-- TOC entry 4393 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN "FCash".refreceipt; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCash".refreceipt IS 'เลขที่ใบเสร็จที่ออกใน fr หรือ fotherpay ถ้ายังไม่ออกจะเป็น null';


--
-- TOC entry 425 (class 1259 OID 68809)
-- Dependencies: 3407 3408 3409 15
-- Name: FCash_Backup; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FCash_Backup" (
    auto_id integer NOT NULL,
    "PostID" character varying(12) NOT NULL,
    "CusID" character varying(12) NOT NULL,
    "IDNO" character varying(25) DEFAULT NULL::character varying,
    "TypePay" smallint NOT NULL,
    "AmtPay" double precision NOT NULL,
    refreceipt character varying(10) DEFAULT NULL::character varying,
    cancel boolean DEFAULT false
);


ALTER TABLE public."FCash_Backup" OWNER TO dev;

--
-- TOC entry 4394 (class 0 OID 0)
-- Dependencies: 425
-- Name: COLUMN "FCash_Backup"."CusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCash_Backup"."CusID" IS 'id ลูกค้าใน fa1';


--
-- TOC entry 4395 (class 0 OID 0)
-- Dependencies: 425
-- Name: COLUMN "FCash_Backup"."IDNO"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCash_Backup"."IDNO" IS 'ถ้าเป็น null แสดงว่าเป็นลูกค้านอกจะมีแต่ cusid';


--
-- TOC entry 4396 (class 0 OID 0)
-- Dependencies: 425
-- Name: COLUMN "FCash_Backup".refreceipt; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCash_Backup".refreceipt IS 'เลขที่ใบเสร็จที่ออกใน fr หรือ fotherpay ถ้ายังไม่ออกจะเป็น null';


--
-- TOC entry 424 (class 1259 OID 68807)
-- Dependencies: 15 425
-- Name: FCash_Backup_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "FCash_Backup_auto_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."FCash_Backup_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4397 (class 0 OID 0)
-- Dependencies: 424
-- Name: FCash_Backup_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "FCash_Backup_auto_id_seq" OWNED BY "FCash_Backup".auto_id;


--
-- TOC entry 283 (class 1259 OID 65080)
-- Dependencies: 282 15
-- Name: FCash_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "FCash_auto_id_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."FCash_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4398 (class 0 OID 0)
-- Dependencies: 283
-- Name: FCash_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "FCash_auto_id_seq" OWNED BY "FCash".auto_id;


--
-- TOC entry 284 (class 1259 OID 65082)
-- Dependencies: 3284 3285 3286 3287 3288 15
-- Name: FCheque; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FCheque" (
    "PostID" character varying(12) NOT NULL,
    "ChequeNo" character varying(10) NOT NULL,
    "BankName" character varying(15),
    "BankBranch" character varying(30),
    "AmtOnCheque" double precision,
    "ReceiptDate" date,
    "DateOnCheque" date,
    "OutBangkok" boolean,
    "ReEnterDate" date,
    "NumOfReEnter" smallint DEFAULT 0,
    "IsPass" boolean DEFAULT false,
    "Accept" boolean DEFAULT true,
    "IsReturn" boolean DEFAULT false,
    "PassByUser" character varying(12) DEFAULT NULL::character varying,
    "DateEnterBank" date,
    "AccBankEnter" character varying(20),
    memo text
);


ALTER TABLE public."FCheque" OWNER TO dev;

--
-- TOC entry 4399 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN "FCheque"."NumOfReEnter"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCheque"."NumOfReEnter" IS 'จำนวนครั้งที่ยื่นเช็คเข้าใหม่';


--
-- TOC entry 4400 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN "FCheque"."IsPass"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCheque"."IsPass" IS 'เช็คมีการเรียกชำระแล้วเป็น true  ยังไม่ผ่านเป็น false';


--
-- TOC entry 4401 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN "FCheque"."Accept"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCheque"."Accept" IS 'รับกับลูกค้าไว้ เป็น true  ถ้าคืนลูกค้าเป็น false';


--
-- TOC entry 4402 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN "FCheque"."IsReturn"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCheque"."IsReturn" IS 'ถ้ามีการเช็คไม่ผ่านจากธนาคารเป็น true';


--
-- TOC entry 4403 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN "FCheque"."PassByUser"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCheque"."PassByUser" IS 'user which pass cheque to receipt';


--
-- TOC entry 4404 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN "FCheque"."DateEnterBank"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCheque"."DateEnterBank" IS 'วันที่นำเช็คเข้าธนาคาร';


--
-- TOC entry 4405 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN "FCheque"."AccBankEnter"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FCheque"."AccBankEnter" IS 'เลขที่บัญชีที่เข้าของบริษัท';


--
-- TOC entry 285 (class 1259 OID 65093)
-- Dependencies: 15
-- Name: FGas; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FGas" (
    "GasID" character varying(12) NOT NULL,
    gas_name character varying(50) NOT NULL,
    gas_number character varying(20) NOT NULL,
    gas_type character varying(20) NOT NULL,
    car_regis character varying(10) NOT NULL,
    car_regis_by character varying(15) NOT NULL,
    car_year character varying(6) NOT NULL,
    carnum character varying(22) NOT NULL,
    marnum character varying(20) NOT NULL
);


ALTER TABLE public."FGas" OWNER TO dev;

--
-- TOC entry 4406 (class 0 OID 0)
-- Dependencies: 285
-- Name: COLUMN "FGas".gas_name; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FGas".gas_name IS 'ยี่ห้อถังแก๊ส';


--
-- TOC entry 4407 (class 0 OID 0)
-- Dependencies: 285
-- Name: COLUMN "FGas".gas_number; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FGas".gas_number IS 'เลขถังแก๊สที่ติด';


--
-- TOC entry 4408 (class 0 OID 0)
-- Dependencies: 285
-- Name: COLUMN "FGas".gas_type; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FGas".gas_type IS 'รุ่นของแก๊สที่ใช้ เช่น ngv , lpg';


--
-- TOC entry 4409 (class 0 OID 0)
-- Dependencies: 285
-- Name: COLUMN "FGas".car_regis; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FGas".car_regis IS 'ทะเบียนรถที่ติดถังแก๊ส';


--
-- TOC entry 4410 (class 0 OID 0)
-- Dependencies: 285
-- Name: COLUMN "FGas".car_regis_by; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FGas".car_regis_by IS 'รถที่ติดถังจดทะเบียนจังหวัด';


--
-- TOC entry 4411 (class 0 OID 0)
-- Dependencies: 285
-- Name: COLUMN "FGas".car_year; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FGas".car_year IS 'ปีของรถที่ติด';


--
-- TOC entry 4412 (class 0 OID 0)
-- Dependencies: 285
-- Name: COLUMN "FGas".carnum; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FGas".carnum IS 'เลขตัวถังรถยนต์ที่ิติดถังแก๋ส';


--
-- TOC entry 4413 (class 0 OID 0)
-- Dependencies: 285
-- Name: COLUMN "FGas".marnum; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FGas".marnum IS 'เลขเครื่องยนต์ของรถที่ติดถังแก๊ส';


--
-- TOC entry 286 (class 1259 OID 65096)
-- Dependencies: 3289 3290 3291 3292 3293 3294 15
-- Name: FReceiptNO; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FReceiptNO" (
    "Rec_date" date NOT NULL,
    "R" integer DEFAULT 0 NOT NULL,
    "N" integer DEFAULT 0 NOT NULL,
    "V" integer DEFAULT 0 NOT NULL,
    "K" integer DEFAULT 0 NOT NULL,
    "P" integer DEFAULT 0 NOT NULL,
    "C" integer DEFAULT 0 NOT NULL
);


ALTER TABLE public."FReceiptNO" OWNER TO dev;

--
-- TOC entry 4414 (class 0 OID 0)
-- Dependencies: 286
-- Name: COLUMN "FReceiptNO"."P"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FReceiptNO"."P" IS 'postID';


--
-- TOC entry 287 (class 1259 OID 65105)
-- Dependencies: 3295 3296 15
-- Name: FTACCheque; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FTACCheque" (
    auto_id integer NOT NULL,
    "PostID" character varying(12) NOT NULL,
    "COID" character varying(25) NOT NULL,
    "TypePay" smallint NOT NULL,
    "AmtPay" double precision NOT NULL,
    refreceipt character varying(10) DEFAULT NULL::character varying,
    "D_ChequeNo" character varying(15) NOT NULL,
    "D_BankName" character varying(15) NOT NULL,
    "D_BankBranch" character varying(15) NOT NULL,
    "D_DateEntBank" date NOT NULL,
    cancel boolean DEFAULT false,
    fullname character varying(200),
    carregis character varying(15)
);


ALTER TABLE public."FTACCheque" OWNER TO dev;

--
-- TOC entry 4415 (class 0 OID 0)
-- Dependencies: 287
-- Name: COLUMN "FTACCheque"."COID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACCheque"."COID" IS 'IDNO - กรณีเป็นลูกค้าที่มีสัญญาเช่าซื้อ
COID - กรณีเป็นลูกค้านอก (วิทยุ)';


--
-- TOC entry 4416 (class 0 OID 0)
-- Dependencies: 287
-- Name: COLUMN "FTACCheque"."TypePay"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACCheque"."TypePay" IS 'รหัสประเภทการจ่าย';


--
-- TOC entry 4417 (class 0 OID 0)
-- Dependencies: 287
-- Name: COLUMN "FTACCheque"."AmtPay"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACCheque"."AmtPay" IS 'จำนวนเงินที่จ่าย';


--
-- TOC entry 4418 (class 0 OID 0)
-- Dependencies: 287
-- Name: COLUMN "FTACCheque".fullname; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACCheque".fullname IS 'ชื่อลูกค้า';


--
-- TOC entry 4419 (class 0 OID 0)
-- Dependencies: 287
-- Name: COLUMN "FTACCheque".carregis; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACCheque".carregis IS 'ทะเบียนรถ';


--
-- TOC entry 288 (class 1259 OID 65110)
-- Dependencies: 15 287
-- Name: FTACCheque_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "FTACCheque_auto_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."FTACCheque_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4420 (class 0 OID 0)
-- Dependencies: 288
-- Name: FTACCheque_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "FTACCheque_auto_id_seq" OWNED BY "FTACCheque".auto_id;


--
-- TOC entry 289 (class 1259 OID 65112)
-- Dependencies: 3298 3299 15
-- Name: FTACTran; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FTACTran" (
    auto_id integer NOT NULL,
    "PostID" character varying(12) NOT NULL,
    "COID" character varying(25) NOT NULL,
    fullname character varying(200) NOT NULL,
    carregis character varying(15) NOT NULL,
    "TypePay" smallint NOT NULL,
    "AmtPay" double precision NOT NULL,
    refreceipt character varying(10) DEFAULT NULL::character varying,
    "D_BankName" character varying(50) NOT NULL,
    "D_BankAccount" character varying(20) NOT NULL,
    "D_DatetimeEnterBank" timestamp without time zone,
    cancel boolean DEFAULT false
);


ALTER TABLE public."FTACTran" OWNER TO dev;

--
-- TOC entry 4421 (class 0 OID 0)
-- Dependencies: 289
-- Name: COLUMN "FTACTran"."COID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACTran"."COID" IS 'IDNO / COID วิทยุลูกค้านอก';


--
-- TOC entry 4422 (class 0 OID 0)
-- Dependencies: 289
-- Name: COLUMN "FTACTran".fullname; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACTran".fullname IS 'คำนำหน้า-ชื่อ-นามสกุล ที่แสดงบนใบเสร็จ';


--
-- TOC entry 4423 (class 0 OID 0)
-- Dependencies: 289
-- Name: COLUMN "FTACTran".carregis; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACTran".carregis IS 'ทะเบียนรถที่แสดงบนใบเสร็จ';


--
-- TOC entry 4424 (class 0 OID 0)
-- Dependencies: 289
-- Name: COLUMN "FTACTran"."AmtPay"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACTran"."AmtPay" IS 'จำนวนเงินที่จ่าย บนใบเสร็จ';


--
-- TOC entry 4425 (class 0 OID 0)
-- Dependencies: 289
-- Name: COLUMN "FTACTran".refreceipt; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACTran".refreceipt IS 'เลขที่ใบเสร็จที่ออกใน fr หรือ fotherpay ถ้ายังไม่ออกจะเป็น null';


--
-- TOC entry 4426 (class 0 OID 0)
-- Dependencies: 289
-- Name: COLUMN "FTACTran"."D_BankName"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACTran"."D_BankName" IS 'ชื่อธนาคาร';


--
-- TOC entry 4427 (class 0 OID 0)
-- Dependencies: 289
-- Name: COLUMN "FTACTran"."D_BankAccount"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACTran"."D_BankAccount" IS 'เลขที่บัญชีธนาคาร';


--
-- TOC entry 4428 (class 0 OID 0)
-- Dependencies: 289
-- Name: COLUMN "FTACTran"."D_DatetimeEnterBank"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "FTACTran"."D_DatetimeEnterBank" IS 'วันเวลาที่โอนเงินเข้าธนาคาร';


--
-- TOC entry 290 (class 1259 OID 65117)
-- Dependencies: 289 15
-- Name: FTACTran_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "FTACTran_auto_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."FTACTran_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4429 (class 0 OID 0)
-- Dependencies: 290
-- Name: FTACTran_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "FTACTran_auto_id_seq" OWNED BY "FTACTran".auto_id;


--
-- TOC entry 429 (class 1259 OID 68847)
-- Dependencies: 3411 15
-- Name: Fa1_Backup; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fa1_Backup" (
    "CusID" character(12) NOT NULL,
    "A_FIRNAME" character(20),
    "A_NAME" character(60),
    "A_SIRNAME" character(60),
    "A_PAIR" character(120),
    "A_NO" character(15),
    "A_SUBNO" character(10),
    "A_SOI" character(60),
    "A_RD" character(60),
    "A_TUM" character(60),
    "A_AUM" character(60),
    "A_PRO" character(60),
    "A_POST" character varying(10),
    "Approved" boolean DEFAULT false NOT NULL
);


ALTER TABLE public."Fa1_Backup" OWNER TO dev;

--
-- TOC entry 435 (class 1259 OID 68950)
-- Dependencies: 3437 15
-- Name: Fa1_temp; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fa1_temp" (
    "CusID" character(12) NOT NULL,
    "A_FIRNAME" character(20),
    "A_NAME" character(60),
    "A_SIRNAME" character(60),
    "A_PAIR" character(120),
    "A_NO" character(15),
    "A_SUBNO" character(10),
    "A_SOI" character(60),
    "A_RD" character(60),
    "A_TUM" character(60),
    "A_AUM" character(60),
    "A_PRO" character(60),
    "A_POST" character varying(10),
    "Approved" boolean DEFAULT false NOT NULL
);


ALTER TABLE public."Fa1_temp" OWNER TO dev;

--
-- TOC entry 291 (class 1259 OID 65119)
-- Dependencies: 3301 3302 15
-- Name: Fn; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fn" (
    "CusID" character varying(12) NOT NULL,
    "N_STATE" smallint NOT NULL,
    "N_SAN" character varying(60),
    "N_AGE" smallint DEFAULT 0,
    "N_CARD" character varying(30),
    "N_IDCARD" character varying(20),
    "N_OT_DATE" date DEFAULT ('now'::text)::date,
    "N_BY" character varying(60),
    "N_OCC" character varying(60),
    "N_ContactAdd" text
);


ALTER TABLE public."Fn" OWNER TO dev;

--
-- TOC entry 430 (class 1259 OID 68858)
-- Dependencies: 3412 3413 15
-- Name: Fn_Backup; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fn_Backup" (
    "CusID" character varying(12) NOT NULL,
    "N_STATE" smallint NOT NULL,
    "N_SAN" character varying(60),
    "N_AGE" smallint DEFAULT 0,
    "N_CARD" character varying(30),
    "N_IDCARD" character varying(20),
    "N_OT_DATE" date DEFAULT ('now'::text)::date,
    "N_BY" character varying(60),
    "N_OCC" character varying(60),
    "N_ContactAdd" text
);


ALTER TABLE public."Fn_Backup" OWNER TO dev;

--
-- TOC entry 436 (class 1259 OID 68961)
-- Dependencies: 3438 3439 15
-- Name: Fn_temp; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fn_temp" (
    "CusID" character varying(12) NOT NULL,
    "N_STATE" smallint NOT NULL,
    "N_SAN" character varying(60),
    "N_AGE" smallint DEFAULT 0,
    "N_CARD" character varying(30),
    "N_IDCARD" character varying(20),
    "N_OT_DATE" date DEFAULT ('now'::text)::date,
    "N_BY" character varying(60),
    "N_OCC" character varying(60),
    "N_ContactAdd" text
);


ALTER TABLE public."Fn_temp" OWNER TO dev;

--
-- TOC entry 292 (class 1259 OID 65127)
-- Dependencies: 15
-- Name: FollowUpCus; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FollowUpCus" (
    auto_id integer NOT NULL,
    "FollowDate" timestamp without time zone NOT NULL,
    "GroupID" character varying(3) NOT NULL,
    userid character varying(10) NOT NULL,
    "IDNO" character varying(25),
    "CusID" character varying(12),
    "FollowDetail" text NOT NULL
);


ALTER TABLE public."FollowUpCus" OWNER TO dev;

--
-- TOC entry 427 (class 1259 OID 68823)
-- Dependencies: 15
-- Name: FollowUpCus_Backup; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "FollowUpCus_Backup" (
    auto_id integer NOT NULL,
    "FollowDate" timestamp without time zone NOT NULL,
    "GroupID" character varying(3) NOT NULL,
    userid character varying(10) NOT NULL,
    "IDNO" character varying(25),
    "CusID" character varying(12),
    "FollowDetail" text NOT NULL
);


ALTER TABLE public."FollowUpCus_Backup" OWNER TO dev;

--
-- TOC entry 426 (class 1259 OID 68821)
-- Dependencies: 15 427
-- Name: FollowUpCus_Backup_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "FollowUpCus_Backup_auto_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."FollowUpCus_Backup_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4430 (class 0 OID 0)
-- Dependencies: 426
-- Name: FollowUpCus_Backup_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "FollowUpCus_Backup_auto_id_seq" OWNED BY "FollowUpCus_Backup".auto_id;


--
-- TOC entry 293 (class 1259 OID 65133)
-- Dependencies: 15 292
-- Name: FollowUpCus_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "FollowUpCus_auto_id_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."FollowUpCus_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4431 (class 0 OID 0)
-- Dependencies: 293
-- Name: FollowUpCus_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "FollowUpCus_auto_id_seq" OWNED BY "FollowUpCus".auto_id;


--
-- TOC entry 419 (class 1259 OID 68747)
-- Dependencies: 3379 3380 3381 3382 3383 3384 3385 3386 3387 3388 3389 3390 3391 3392 3393 3394 3395 3396 3397 3398 3399 3400 3401 15
-- Name: Fp_Backup; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fp_Backup" (
    "IDNO" character varying(25) NOT NULL,
    "TranIDRef1" character varying(15),
    "TranIDRef2" character varying(15),
    "PayCon" character varying(5),
    "AccType" character varying(5),
    "P_STDATE" date,
    "P_MONTH" double precision DEFAULT 0,
    "P_VAT" double precision DEFAULT 0,
    "P_TOTAL" smallint DEFAULT 0,
    "P_DOWN" double precision DEFAULT 0,
    "P_VatOfDown" double precision DEFAULT 0,
    "P_BEGIN" double precision DEFAULT 0,
    "P_BEGINX" double precision DEFAULT 0,
    "P_FDATE" date,
    "P_OP_PRE" double precision,
    "P_CLDATE" date,
    "P_SL" double precision DEFAULT 0,
    "P_LAWERFEEAmt" double precision DEFAULT 0,
    "P_RECONTRACT_AMT" double precision DEFAULT 0,
    "P_QuestProcess" character(1),
    "P_QuestProcess_AMT" double precision DEFAULT 0,
    "P_TransferFee" double precision DEFAULT 0,
    "P_TransferIDNO" character varying(25),
    "P_StopVatDate" date,
    "P_CtrlBy" character varying(10),
    "P_CustByYear" smallint,
    "PayType" character varying(10),
    "WriteOffDate" date,
    "EffRate" double precision DEFAULT 0,
    "Comm" double precision DEFAULT 0,
    "PathPDFFile" character varying(100),
    "ComeFrom" character varying(100),
    "P_ACCLOSE" boolean DEFAULT false NOT NULL,
    "P_StopVat" boolean DEFAULT false NOT NULL,
    "WriteOff" boolean DEFAULT false NOT NULL,
    "P_LAWERFEE" boolean DEFAULT false NOT NULL,
    "P_TAXABLE" boolean DEFAULT false NOT NULL,
    "LockContact" boolean DEFAULT false NOT NULL,
    "CusID" character varying(12) NOT NULL,
    asset_type smallint DEFAULT 1 NOT NULL,
    asset_id character varying(12) DEFAULT NULL::character varying,
    repo boolean DEFAULT false NOT NULL,
    repo_date date,
    "creditType" character varying(35)
);


ALTER TABLE public."Fp_Backup" OWNER TO dev;

--
-- TOC entry 4432 (class 0 OID 0)
-- Dependencies: 419
-- Name: TABLE "Fp_Backup"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "Fp_Backup" IS '

';


--
-- TOC entry 4433 (class 0 OID 0)
-- Dependencies: 419
-- Name: COLUMN "Fp_Backup"."Comm"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_Backup"."Comm" IS 'ค่านายหน้าในการขายรถ
';


--
-- TOC entry 4434 (class 0 OID 0)
-- Dependencies: 419
-- Name: COLUMN "Fp_Backup"."ComeFrom"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_Backup"."ComeFrom" IS 'ซื้อรถคันนี้มาจากที่ไหน';


--
-- TOC entry 4435 (class 0 OID 0)
-- Dependencies: 419
-- Name: COLUMN "Fp_Backup".asset_id; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_Backup".asset_id IS 'id of any asset  type1:taxi =tax00001 , type2:gas = gas00001';


--
-- TOC entry 4436 (class 0 OID 0)
-- Dependencies: 419
-- Name: COLUMN "Fp_Backup".repo; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_Backup".repo IS 'รถยึดคืน  = true  ';


--
-- TOC entry 4437 (class 0 OID 0)
-- Dependencies: 419
-- Name: COLUMN "Fp_Backup".repo_date; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_Backup".repo_date IS 'วันที่ยึดรถ';


--
-- TOC entry 294 (class 1259 OID 65135)
-- Dependencies: 15
-- Name: Fp_Note; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fp_Note" (
    "IDNO" character varying(25) NOT NULL,
    "ContactNote" text
);


ALTER TABLE public."Fp_Note" OWNER TO dev;

--
-- TOC entry 417 (class 1259 OID 68002)
-- Dependencies: 3354 3355 3356 3357 3358 3359 3360 3361 3362 3363 3364 3365 3366 3367 3368 3369 3370 3371 3372 3373 3374 3375 3376 15
-- Name: Fp_TestMigrate; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Fp_TestMigrate" (
    "IDNO" character varying(25) NOT NULL,
    "TranIDRef1" character varying(15),
    "TranIDRef2" character varying(15),
    "PayCon" character varying(5),
    "AccType" character varying(5),
    "P_STDATE" date,
    "P_MONTH" double precision DEFAULT 0,
    "P_VAT" double precision DEFAULT 0,
    "P_TOTAL" smallint DEFAULT 0,
    "P_DOWN" double precision DEFAULT 0,
    "P_VatOfDown" double precision DEFAULT 0,
    "P_BEGIN" double precision DEFAULT 0,
    "P_BEGINX" double precision DEFAULT 0,
    "P_FDATE" date,
    "P_OP_PRE" double precision,
    "P_CLDATE" date,
    "P_SL" double precision DEFAULT 0,
    "P_LAWERFEEAmt" double precision DEFAULT 0,
    "P_RECONTRACT_AMT" double precision DEFAULT 0,
    "P_QuestProcess" character(1),
    "P_QuestProcess_AMT" double precision DEFAULT 0,
    "P_TransferFee" double precision DEFAULT 0,
    "P_TransferIDNO" character varying(25),
    "P_StopVatDate" date,
    "P_CtrlBy" character varying(10),
    "P_CustByYear" smallint,
    "PayType" character varying(10),
    "WriteOffDate" date,
    "EffRate" double precision DEFAULT 0,
    "Comm" double precision DEFAULT 0,
    "PathPDFFile" character varying(100),
    "ComeFrom" character varying(100),
    "P_ACCLOSE" boolean DEFAULT false NOT NULL,
    "P_StopVat" boolean DEFAULT false NOT NULL,
    "WriteOff" boolean DEFAULT false NOT NULL,
    "P_LAWERFEE" boolean DEFAULT false NOT NULL,
    "P_TAXABLE" boolean DEFAULT false NOT NULL,
    "LockContact" boolean DEFAULT false NOT NULL,
    "CusID" character varying(12) NOT NULL,
    asset_type smallint DEFAULT 1 NOT NULL,
    asset_id character varying(12) DEFAULT NULL::character varying,
    repo boolean DEFAULT false NOT NULL,
    repo_date date,
    "creditType" character varying(35)
);


ALTER TABLE public."Fp_TestMigrate" OWNER TO dev;

--
-- TOC entry 4438 (class 0 OID 0)
-- Dependencies: 417
-- Name: TABLE "Fp_TestMigrate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "Fp_TestMigrate" IS '

';


--
-- TOC entry 4439 (class 0 OID 0)
-- Dependencies: 417
-- Name: COLUMN "Fp_TestMigrate"."Comm"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_TestMigrate"."Comm" IS 'ค่านายหน้าในการขายรถ
';


--
-- TOC entry 4440 (class 0 OID 0)
-- Dependencies: 417
-- Name: COLUMN "Fp_TestMigrate"."ComeFrom"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_TestMigrate"."ComeFrom" IS 'ซื้อรถคันนี้มาจากที่ไหน';


--
-- TOC entry 4441 (class 0 OID 0)
-- Dependencies: 417
-- Name: COLUMN "Fp_TestMigrate".asset_id; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_TestMigrate".asset_id IS 'id of any asset  type1:taxi =tax00001 , type2:gas = gas00001';


--
-- TOC entry 4442 (class 0 OID 0)
-- Dependencies: 417
-- Name: COLUMN "Fp_TestMigrate".repo; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_TestMigrate".repo IS 'รถยึดคืน  = true  ';


--
-- TOC entry 4443 (class 0 OID 0)
-- Dependencies: 417
-- Name: COLUMN "Fp_TestMigrate".repo_date; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Fp_TestMigrate".repo_date IS 'วันที่ยึดรถ';


--
-- TOC entry 295 (class 1259 OID 65141)
-- Dependencies: 15
-- Name: GroupCus; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "GroupCus" (
    "GroupCusID" character varying(25) NOT NULL,
    "GKeyNum" smallint,
    "GKeyChar" character varying(25),
    "GStatus" character varying(10) NOT NULL,
    "GType" character varying(50) NOT NULL
);


ALTER TABLE public."GroupCus" OWNER TO dev;

--
-- TOC entry 4444 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN "GroupCus"."GKeyNum"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus"."GKeyNum" IS '- Future uses (Num)';


--
-- TOC entry 4445 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN "GroupCus"."GKeyChar"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus"."GKeyChar" IS '- Future uses (Char)';


--
-- TOC entry 4446 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN "GroupCus"."GStatus"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus"."GStatus" IS 'สถานะของ GroupCus ว่า ยังอยู่ใน Active หรือ Bin
ถ้า A - Active / B - Bin';


--
-- TOC entry 4447 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN "GroupCus"."GType"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus"."GType" IS 'ประเภทสัญญา';


--
-- TOC entry 296 (class 1259 OID 65144)
-- Dependencies: 15
-- Name: GroupCus_Active; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "GroupCus_Active" (
    "GroupCusID" character varying(25) NOT NULL,
    "CusState" smallint NOT NULL,
    "CusID" character varying(25) NOT NULL
);


ALTER TABLE public."GroupCus_Active" OWNER TO dev;

--
-- TOC entry 4448 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN "GroupCus_Active"."GroupCusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus_Active"."GroupCusID" IS 'รหัสกลุ่มลูกค้า';


--
-- TOC entry 4449 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN "GroupCus_Active"."CusState"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus_Active"."CusState" IS 'รหัสความสำคัญของลูกค้า';


--
-- TOC entry 4450 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN "GroupCus_Active"."CusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus_Active"."CusID" IS 'รหัสลูกค้า';


--
-- TOC entry 428 (class 1259 OID 68842)
-- Dependencies: 15
-- Name: GroupCus_Active_Backup; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "GroupCus_Active_Backup" (
    "GroupCusID" character varying(25) NOT NULL,
    "CusState" smallint NOT NULL,
    "CusID" character varying(25) NOT NULL
);


ALTER TABLE public."GroupCus_Active_Backup" OWNER TO dev;

--
-- TOC entry 4451 (class 0 OID 0)
-- Dependencies: 428
-- Name: COLUMN "GroupCus_Active_Backup"."GroupCusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus_Active_Backup"."GroupCusID" IS 'รหัสกลุ่มลูกค้า';


--
-- TOC entry 4452 (class 0 OID 0)
-- Dependencies: 428
-- Name: COLUMN "GroupCus_Active_Backup"."CusState"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus_Active_Backup"."CusState" IS 'รหัสความสำคัญของลูกค้า';


--
-- TOC entry 4453 (class 0 OID 0)
-- Dependencies: 428
-- Name: COLUMN "GroupCus_Active_Backup"."CusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus_Active_Backup"."CusID" IS 'รหัสลูกค้า';


--
-- TOC entry 297 (class 1259 OID 65147)
-- Dependencies: 15
-- Name: GroupCus_Bin; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "GroupCus_Bin" (
    "GroupCusID" character varying(25) NOT NULL,
    "CusState" smallint NOT NULL,
    "CusID" character varying(25) NOT NULL
);


ALTER TABLE public."GroupCus_Bin" OWNER TO dev;

--
-- TOC entry 4454 (class 0 OID 0)
-- Dependencies: 297
-- Name: COLUMN "GroupCus_Bin"."GroupCusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus_Bin"."GroupCusID" IS 'รหัสกลุ่มลูกค้า';


--
-- TOC entry 4455 (class 0 OID 0)
-- Dependencies: 297
-- Name: COLUMN "GroupCus_Bin"."CusState"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus_Bin"."CusState" IS 'รหัสความสำคัญของลูกค้า';


--
-- TOC entry 4456 (class 0 OID 0)
-- Dependencies: 297
-- Name: COLUMN "GroupCus_Bin"."CusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "GroupCus_Bin"."CusID" IS 'รหัสลูกค้า';


--
-- TOC entry 298 (class 1259 OID 65150)
-- Dependencies: 15
-- Name: LogsAnyFunction; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "LogsAnyFunction" (
    auto_id integer NOT NULL,
    id_menu character varying(5),
    user_id character varying(10),
    time_open timestamp without time zone,
    ref_id character varying(25),
    time_close timestamp without time zone
);


ALTER TABLE public."LogsAnyFunction" OWNER TO dev;

--
-- TOC entry 299 (class 1259 OID 65153)
-- Dependencies: 15 298
-- Name: LogsAnyFunction_auto_id_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "LogsAnyFunction_auto_id_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."LogsAnyFunction_auto_id_seq" OWNER TO dev;

--
-- TOC entry 4457 (class 0 OID 0)
-- Dependencies: 299
-- Name: LogsAnyFunction_auto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "LogsAnyFunction_auto_id_seq" OWNED BY "LogsAnyFunction".auto_id;


--
-- TOC entry 300 (class 1259 OID 65155)
-- Dependencies: 15
-- Name: MRR; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "MRR" (
    "DateInput" date NOT NULL,
    "MRR" numeric(10,4),
    "EffRate" numeric(10,4)
);


ALTER TABLE public."MRR" OWNER TO dev;

--
-- TOC entry 301 (class 1259 OID 65158)
-- Dependencies: 3305 3306 15
-- Name: NTDetail; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "NTDetail" (
    autoid integer NOT NULL,
    "NTID" character varying(12),
    "Detail" character varying(200),
    "Amount" double precision DEFAULT 0,
    "MainDetail" boolean DEFAULT true
);


ALTER TABLE public."NTDetail" OWNER TO dev;

--
-- TOC entry 4458 (class 0 OID 0)
-- Dependencies: 301
-- Name: COLUMN "NTDetail"."MainDetail"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "NTDetail"."MainDetail" IS 'รายการหลักในหนังสือได้แก่ค่าต่างๆที่ต้องชำระ = true
รายการเพิ่มเติมหากพ้นกำหนดในวันที่ใดวันที่หนึ่ง = false';


--
-- TOC entry 302 (class 1259 OID 65163)
-- Dependencies: 301 15
-- Name: NTDetail_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "NTDetail_autoid_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."NTDetail_autoid_seq" OWNER TO dev;

--
-- TOC entry 4459 (class 0 OID 0)
-- Dependencies: 302
-- Name: NTDetail_autoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "NTDetail_autoid_seq" OWNED BY "NTDetail".autoid;


--
-- TOC entry 303 (class 1259 OID 65165)
-- Dependencies: 3308 15
-- Name: NTHead; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "NTHead" (
    "NTID" character varying(12) NOT NULL,
    "IDNO" character varying(25),
    do_date date,
    to_date date,
    cancel boolean DEFAULT false,
    remark text,
    makerid character varying(10),
    "CusState" smallint,
    cancelid character varying(10),
    cancel_date date,
    remine_date date
);


ALTER TABLE public."NTHead" OWNER TO dev;

--
-- TOC entry 4460 (class 0 OID 0)
-- Dependencies: 303
-- Name: COLUMN "NTHead".makerid; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "NTHead".makerid IS 'ID ผู้ออกหนังสือ';


--
-- TOC entry 4461 (class 0 OID 0)
-- Dependencies: 303
-- Name: COLUMN "NTHead".cancelid; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "NTHead".cancelid IS 'ID ของผู้ที่ยกเลิกหนังสือ';


--
-- TOC entry 4462 (class 0 OID 0)
-- Dependencies: 303
-- Name: COLUMN "NTHead".remine_date; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "NTHead".remine_date IS 'งวดที่เริ่มค้างตามหนังสือที่ออก';


--
-- TOC entry 304 (class 1259 OID 65172)
-- Dependencies: 15
-- Name: PayType; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "PayType" (
    "PayTypeID" character varying(6) NOT NULL,
    "PayTypeName" character varying(100),
    "PayTypeDesc" character varying(200)
);


ALTER TABLE public."PayType" OWNER TO dev;

--
-- TOC entry 305 (class 1259 OID 65175)
-- Dependencies: 15
-- Name: PayTypeFromAnyPlace; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "PayTypeFromAnyPlace" (
    ptanyplace character varying(50) NOT NULL
);


ALTER TABLE public."PayTypeFromAnyPlace" OWNER TO dev;

--
-- TOC entry 306 (class 1259 OID 65178)
-- Dependencies: 3309 3310 15
-- Name: PostLog; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "PostLog" (
    "PostID" character varying(12) NOT NULL,
    "UserIDPost" character varying(12) NOT NULL,
    "UserIDAccept" character varying(12),
    "PostDate" date NOT NULL,
    paytype character varying(10) DEFAULT 'CA'::character varying NOT NULL,
    "AcceptPost" boolean DEFAULT false NOT NULL,
    "PostTime" time without time zone
);


ALTER TABLE public."PostLog" OWNER TO dev;

--
-- TOC entry 4463 (class 0 OID 0)
-- Dependencies: 306
-- Name: COLUMN "PostLog".paytype; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "PostLog".paytype IS 'CA=เงินสด,  CH=เช็ค , TR= เงินโอน';


--
-- TOC entry 4464 (class 0 OID 0)
-- Dependencies: 306
-- Name: COLUMN "PostLog"."AcceptPost"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "PostLog"."AcceptPost" IS 'สถานะการรับเงินหลังจากpostแล้ว เป็น true';


--
-- TOC entry 307 (class 1259 OID 65183)
-- Dependencies: 15
-- Name: RadioContract; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "RadioContract" (
    "COID" character varying(25) NOT NULL,
    "RadioNum" character varying(10) NOT NULL,
    "RadioCar" character varying(20) NOT NULL,
    "RadioRelationID" character varying(25) NOT NULL,
    "ContractStatus" smallint,
    "ContractCtl" smallint,
    "ContractDesc" character varying(254),
    "DoerID" character varying(25) NOT NULL,
    "DoerStamp" timestamp without time zone NOT NULL,
    "AppvID" character varying(25),
    "AppvStamp" timestamp without time zone,
    "AuditID" character varying(25),
    "AuditStamp" timestamp without time zone,
    "AppvRemask" text
);


ALTER TABLE public."RadioContract" OWNER TO dev;

--
-- TOC entry 308 (class 1259 OID 65189)
-- Dependencies: 15
-- Name: RadioContract_Bin; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "RadioContract_Bin" (
    "COID" character varying(25) NOT NULL,
    "RadioNum" character varying(10) NOT NULL,
    "RadioCar" character varying(20) NOT NULL,
    "RadioRelationID" character varying(25) NOT NULL,
    "ContractStatus" smallint,
    "ContractCtl" smallint,
    "ContractDesc" character varying(254),
    "DoerID" character varying(25) NOT NULL,
    "DoerStamp" timestamp without time zone NOT NULL,
    "AppvID" character varying(25),
    "AppvStamp" timestamp without time zone,
    "AuditID" character varying(25),
    "AuditStamp" timestamp without time zone,
    "AppvRemask" text
);


ALTER TABLE public."RadioContract_Bin" OWNER TO dev;

--
-- TOC entry 309 (class 1259 OID 65195)
-- Dependencies: 15
-- Name: TacMail; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "TacMail" (
    "tmCusID" character varying(20) NOT NULL,
    "tmDoc" character varying(20),
    "tmAddType" character varying(10),
    "tmUserID" character varying,
    "tmTimeStamp" timestamp without time zone
);


ALTER TABLE public."TacMail" OWNER TO dev;

--
-- TOC entry 4465 (class 0 OID 0)
-- Dependencies: 309
-- Name: TABLE "TacMail"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "TacMail" IS 'ส่งเอกสาร tac1681';


--
-- TOC entry 4466 (class 0 OID 0)
-- Dependencies: 309
-- Name: COLUMN "TacMail"."tmCusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "TacMail"."tmCusID" IS 'รหัสลูกค้า CusID ที่ทำรายการส่งจดหมาย';


--
-- TOC entry 4467 (class 0 OID 0)
-- Dependencies: 309
-- Name: COLUMN "TacMail"."tmDoc"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "TacMail"."tmDoc" IS 'รหัสเอกสารที่จะส่ง';


--
-- TOC entry 4468 (class 0 OID 0)
-- Dependencies: 309
-- Name: COLUMN "TacMail"."tmAddType"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "TacMail"."tmAddType" IS 'เลขประเภทที่อยู่ที่เลือก 1 / 2 / 3';


--
-- TOC entry 4469 (class 0 OID 0)
-- Dependencies: 309
-- Name: COLUMN "TacMail"."tmUserID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "TacMail"."tmUserID" IS 'userid ที่ทำรายการส่งจดหมาย';


--
-- TOC entry 4470 (class 0 OID 0)
-- Dependencies: 309
-- Name: COLUMN "TacMail"."tmTimeStamp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "TacMail"."tmTimeStamp" IS 'วันเวลาที่ทำรายการ';


--
-- TOC entry 310 (class 1259 OID 65201)
-- Dependencies: 15
-- Name: Taxiacc; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "Taxiacc" (
    "CusID" character varying(12) NOT NULL,
    "statusNT" character(1),
    "cutAccount" double precision,
    "statusLock" character(1),
    "checkDate" date,
    id_user character varying(12),
    "KeyDate" timestamp without time zone,
    "NTDate" date,
    "cutYear" integer,
    ntrec double precision,
    radiostop date
);


ALTER TABLE public."Taxiacc" OWNER TO dev;

--
-- TOC entry 4471 (class 0 OID 0)
-- Dependencies: 310
-- Name: TABLE "Taxiacc"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "Taxiacc" IS 'ตารางเก็บข้อมูลของ ลูกค้า 1681';


--
-- TOC entry 4472 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc"."CusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc"."CusID" IS 'เลขที่สัญญา';


--
-- TOC entry 4473 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc"."statusNT"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc"."statusNT" IS 'เก็บสถานะการออก NT
0=เข้าข่ายแต่ยังไม่ออกหรือไม่ทราบ
1=ออกแล้ว
9=ไม่เข้าข่ายออก';


--
-- TOC entry 4474 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc"."cutAccount"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc"."cutAccount" IS 'เก็บว่าหนี้สูญไปแล้วเท่าไหร่';


--
-- TOC entry 4475 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc"."statusLock"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc"."statusLock" IS 'เก็บสถานะการล็อคสัญญา
0=ไม่ได้ล็อค
1=ล็อคแล้ว
9=ยังไม่ทราบว่าล็อคหรือไม่';


--
-- TOC entry 4476 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc"."checkDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc"."checkDate" IS 'วันที่ที่เช็คศูนย์';


--
-- TOC entry 4477 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc".id_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc".id_user IS 'user ที่ทำรายการล่าสุด';


--
-- TOC entry 4478 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc"."KeyDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc"."KeyDate" IS 'วันเวลาที่ทำรายการ';


--
-- TOC entry 4479 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc"."NTDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc"."NTDate" IS 'วันที่ออก NT ถ้าไม่กรอกให้ระบุเป็น 1900-01-01';


--
-- TOC entry 4480 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc"."cutYear"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc"."cutYear" IS 'ปี ค.ศ.ที่ตัดหนี้สูญ';


--
-- TOC entry 4481 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc".ntrec; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc".ntrec IS 'หนี้สูญรับคืน';


--
-- TOC entry 4482 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN "Taxiacc".radiostop; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "Taxiacc".radiostop IS 'วันที่เลิกสัญญา';


--
-- TOC entry 311 (class 1259 OID 65204)
-- Dependencies: 3311 3312 15
-- Name: TranPay; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "TranPay" (
    branch_id smallint DEFAULT 1 NOT NULL,
    tr_date date NOT NULL,
    tr_time time without time zone NOT NULL,
    pay_bank_branch character varying(10) NOT NULL,
    terminal_id character varying(11) NOT NULL,
    terminal_sq_no character varying(6) NOT NULL,
    ref1 character varying(20),
    ref2 character varying(20),
    amt numeric NOT NULL,
    ref_name character varying(100),
    bank_no character varying(10) NOT NULL,
    tran_type character varying(10) NOT NULL,
    pay_cheque_no character varying(10) NOT NULL,
    post_on_asa_sys boolean DEFAULT false NOT NULL,
    post_on_date date,
    post_to_idno character varying,
    post_by character varying(10),
    "PostID" character varying(12) NOT NULL,
    id_tranpay integer NOT NULL
);


ALTER TABLE public."TranPay" OWNER TO dev;

--
-- TOC entry 312 (class 1259 OID 65212)
-- Dependencies: 15
-- Name: TranPay_audit; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "TranPay_audit" (
    id_tranpay integer NOT NULL,
    result smallint NOT NULL,
    "auditorID" character varying(30),
    "auditStamp" timestamp without time zone,
    "auditRemask" text,
    "auditNum" smallint NOT NULL
);


ALTER TABLE public."TranPay_audit" OWNER TO dev;

--
-- TOC entry 4483 (class 0 OID 0)
-- Dependencies: 312
-- Name: COLUMN "TranPay_audit".result; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "TranPay_audit".result IS '1 - ตรวจแล้วปกติ
9 - ตรวจแล้วมีปัญหา';


--
-- TOC entry 313 (class 1259 OID 65218)
-- Dependencies: 312 15
-- Name: TranPay_audit_id_tranpay_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "TranPay_audit_id_tranpay_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."TranPay_audit_id_tranpay_seq" OWNER TO dev;

--
-- TOC entry 4484 (class 0 OID 0)
-- Dependencies: 313
-- Name: TranPay_audit_id_tranpay_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "TranPay_audit_id_tranpay_seq" OWNED BY "TranPay_audit".id_tranpay;


--
-- TOC entry 314 (class 1259 OID 65220)
-- Dependencies: 311 15
-- Name: TranPay_id_tranpay_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "TranPay_id_tranpay_seq"
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."TranPay_id_tranpay_seq" OWNER TO dev;

--
-- TOC entry 4485 (class 0 OID 0)
-- Dependencies: 314
-- Name: TranPay_id_tranpay_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "TranPay_id_tranpay_seq" OWNED BY "TranPay".id_tranpay;


--
-- TOC entry 315 (class 1259 OID 65222)
-- Dependencies: 3315 15
-- Name: TypeOfAsset; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "TypeOfAsset" (
    asset_type smallint NOT NULL,
    asset_name text NOT NULL,
    asset_preid character varying(3) DEFAULT NULL::character varying
);


ALTER TABLE public."TypeOfAsset" OWNER TO dev;

--
-- TOC entry 4486 (class 0 OID 0)
-- Dependencies: 315
-- Name: TABLE "TypeOfAsset"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "TypeOfAsset" IS 'if you want to add asset   
you must add field in "ContactID"  and  edit function "generate_id(date,integer,integer)"
';


--
-- TOC entry 316 (class 1259 OID 65229)
-- Dependencies: 3316 3317 15
-- Name: TypePay; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "TypePay" (
    "TypeID" smallint NOT NULL,
    "TName" character varying(100) NOT NULL,
    "UseVat" boolean DEFAULT true NOT NULL,
    "TypeRec" character varying(1) DEFAULT 'N'::character varying NOT NULL,
    "TypeDep" character varying(1)
);


ALTER TABLE public."TypePay" OWNER TO dev;

--
-- TOC entry 4487 (class 0 OID 0)
-- Dependencies: 316
-- Name: COLUMN "TypePay"."UseVat"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "TypePay"."UseVat" IS 'ต้องมีการชำระ vat = true  ,ไม่มี vat = false';


--
-- TOC entry 317 (class 1259 OID 65234)
-- Dependencies: 3090 15
-- Name: VContact; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VContact" AS
    SELECT "Fp"."IDNO", ((((btrim(("Fa1"."A_FIRNAME")::text) || ' '::text) || btrim(("Fa1"."A_NAME")::text)) || ' '::text) || btrim(("Fa1"."A_SIRNAME")::text)) AS full_name, "Fp"."TranIDRef1", "Fp"."TranIDRef2", "Fp"."P_STDATE", "Fp"."P_BEGINX", "Fp"."P_BEGIN", "Fp"."P_MONTH", "Fp"."P_VAT", "Fp"."P_TOTAL", "Fp"."P_DOWN", "Fp"."P_VatOfDown", "Fp"."CusID", "Fp".asset_type, "Fp".asset_id, "Fc"."C_CARNAME", "Fc"."C_REGIS", "Fc"."C_CARNUM", "Fc"."C_YEAR", "Fc"."RadioID", "Fc"."C_COLOR", "Fc"."C_TAX_ExpDate", "Fc"."C_StartDate", "FGas".gas_name, "FGas".gas_number, "FGas".car_regis, "FGas".carnum, deposit_balance(("Fp"."IDNO")::text) AS dp_balance, "Fp"."P_ACCLOSE", "Fp"."P_FDATE" FROM ((("Fp" LEFT JOIN "Fa1" ON ((("Fp"."CusID")::bpchar = "Fa1"."CusID"))) LEFT JOIN "Fc" ON ((("Fp".asset_id)::text = ("Fc"."CarID")::text))) LEFT JOIN "FGas" ON ((("Fp".asset_id)::text = ("FGas"."GasID")::text)));


ALTER TABLE public."VContact" OWNER TO dev;

--
-- TOC entry 318 (class 1259 OID 65239)
-- Dependencies: 3091 15
-- Name: VOutCusContact; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VOutCusContact" AS
    SELECT "FpOutCus"."IDNO", ((((btrim(("Fa1"."A_FIRNAME")::text) || ' '::text) || btrim(("Fa1"."A_NAME")::text)) || ' '::text) || btrim(("Fa1"."A_SIRNAME")::text)) AS full_name, "FpOutCus"."OCRef1", "FpOutCus"."OCRef2", "FpOutCus"."ACStartDate", "FpOutCus"."CusID", "FpOutCus"."CarID", "Fc"."C_CARNAME", "Fc"."C_CARNUM", "Fc"."C_REGIS", "Fc"."C_YEAR", "Fc"."RadioID", "Fc"."C_COLOR", "Fc"."C_TAX_ExpDate", "Fc"."C_StartDate", "FpOutCus".asset_type, "FpOutCus"."TypeContact", "FpOutCus"."SignDate" FROM (("FpOutCus" LEFT JOIN "Fa1" ON ((("FpOutCus"."CusID")::bpchar = "Fa1"."CusID"))) LEFT JOIN "Fc" ON ((("FpOutCus"."CarID")::text = ("Fc"."CarID")::text)));


ALTER TABLE public."VOutCusContact" OWNER TO dev;

--
-- TOC entry 319 (class 1259 OID 65244)
-- Dependencies: 3092 15
-- Name: UNContact; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "UNContact" AS
    SELECT "VContact"."IDNO", "VContact".full_name, "VContact"."TranIDRef1", "VContact"."TranIDRef2", "VContact"."P_STDATE", "VContact"."CusID", "VContact".asset_id, "VContact"."C_CARNAME", "VContact"."C_CARNUM", "VContact"."C_REGIS", "VContact"."C_YEAR", "VContact"."RadioID", "VContact"."C_COLOR", "VContact"."C_TAX_ExpDate", "VContact"."C_StartDate", "VContact".asset_type, "VContact"."P_FDATE" FROM "VContact" UNION ALL SELECT "VOutCusContact"."IDNO", "VOutCusContact".full_name, "VOutCusContact"."OCRef1" AS "TranIDRef1", "VOutCusContact"."OCRef2" AS "TranIDRef2", "VOutCusContact"."SignDate" AS "P_STDATE", "VOutCusContact"."CusID", "VOutCusContact"."CarID" AS asset_id, "VOutCusContact"."C_CARNAME", "VOutCusContact"."C_CARNUM", "VOutCusContact"."C_REGIS", "VOutCusContact"."C_YEAR", "VOutCusContact"."RadioID", "VOutCusContact"."C_COLOR", "VOutCusContact"."C_TAX_ExpDate", "VOutCusContact"."C_StartDate", "VOutCusContact".asset_type, "VOutCusContact"."ACStartDate" AS "P_FDATE" FROM "VOutCusContact";


ALTER TABLE public."UNContact" OWNER TO dev;

--
-- TOC entry 320 (class 1259 OID 65249)
-- Dependencies: 3093 15
-- Name: VAccPayment; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VAccPayment" AS
    SELECT "AccPayment"."IDNO", "AccPayment"."DueNo", "AccPayment"."DueDate", "Fr"."R_Date", ("Fr"."R_Date" - "AccPayment"."DueDate") AS daydelay, "CalAmtDelay"("Fr"."R_Date", "AccPayment"."DueDate", ("Fr"."R_Money" + "FVat"."VatValue")) AS "CalAmtDelay", "Fr"."R_Receipt", "FVat"."V_Receipt", "FVat"."V_Date", "Fr"."R_Money", "FVat"."VatValue", round(("AccPayment"."AccuInt")::numeric, 2) AS accint, round(("AccPayment"."WaitIncome")::numeric, 2) AS waitincome, "AccPayment"."CPAcuu" AS commaccu, "AccPayment"."Remine", "AccPayment"."Priciple", "AccPayment"."Interest" FROM (("AccPayment" LEFT JOIN "Fr" ON ((((("AccPayment"."IDNO")::text = ("Fr"."IDNO")::text) AND ("AccPayment"."DueNo" = "Fr"."R_DueNo")) AND ("Fr"."Cancel" = false)))) LEFT JOIN "FVat" ON ((((("AccPayment"."IDNO")::text = ("FVat"."IDNO")::text) AND ("AccPayment"."DueNo" = "FVat"."V_DueNo")) AND ("FVat"."Cancel" = false)))) ORDER BY "AccPayment"."IDNO", "AccPayment"."DueDate";


ALTER TABLE public."VAccPayment" OWNER TO dev;

--
-- TOC entry 321 (class 1259 OID 65254)
-- Dependencies: 3094 15
-- Name: VCostOfCar; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VCostOfCar" AS
    SELECT "Fp"."IDNO", ((((btrim(("Fa1"."A_FIRNAME")::text) || ' '::text) || btrim(("Fa1"."A_NAME")::text)) || ' '::text) || btrim(("Fa1"."A_SIRNAME")::text)), asset_regis(("Fp".asset_type)::integer, ("Fp".asset_id)::text) AS asset_regis, "Fp"."P_STDATE", "Fp"."P_BEGINX", "Fp"."ComeFrom" FROM "Fp", "Fa1" WHERE (("Fp"."CusID")::bpchar = "Fa1"."CusID") ORDER BY "Fp"."IDNO";


ALTER TABLE public."VCostOfCar" OWNER TO dev;

--
-- TOC entry 322 (class 1259 OID 65259)
-- Dependencies: 3095 15
-- Name: VCusPayment; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VCusPayment" AS
    SELECT "CusPayment"."IDNO", "CusPayment"."DueNo", "CusPayment"."DueDate", "Fr"."R_Date", ("Fr"."R_Date" - "CusPayment"."DueDate") AS daydelay, "CalAmtDelay"("Fr"."R_Date", "CusPayment"."DueDate", ("Fr"."R_Money" + "FVat"."VatValue")) AS "CalAmtDelay", "Fr"."R_Receipt", "Fr"."R_Bank", "Fr"."PayType", "FVat"."V_Receipt", "FVat"."V_Date", "Fr"."R_Money", "FVat"."VatValue", "CusPayment"."Remine", "CusPayment"."Priciple", "CusPayment"."Interest", "CusPayment"."AccuInt", "CusPayment"."WaitIncome" FROM (("CusPayment" LEFT JOIN "Fr" ON ((((("CusPayment"."IDNO")::text = ("Fr"."IDNO")::text) AND ("CusPayment"."DueNo" = "Fr"."R_DueNo")) AND ("Fr"."Cancel" = false)))) LEFT JOIN "FVat" ON ((((("CusPayment"."IDNO")::text = ("FVat"."IDNO")::text) AND ("CusPayment"."DueNo" = "FVat"."V_DueNo")) AND ("FVat"."Cancel" = false)))) ORDER BY "CusPayment"."IDNO", "CusPayment"."DueDate";


ALTER TABLE public."VCusPayment" OWNER TO dev;

--
-- TOC entry 323 (class 1259 OID 65264)
-- Dependencies: 3096 15
-- Name: VDepositRemain; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VDepositRemain" AS
    SELECT a."IDNO", a."O_DATE", a."O_RECEIPT", (round((a."O_MONEY" * (100)::double precision)) / (100)::double precision) AS "O_MONEY", (round(((a."O_MONEY" + sum(b."O_MONEY")) * (100)::double precision)) / (100)::double precision) AS remain FROM ("FOtherpay" a LEFT JOIN "FOtherpay" b ON ((((b."O_memo" = (a."O_RECEIPT")::text) AND (b."O_Type" = 299)) AND (b."Cancel" = false)))) WHERE ((a."O_Type" = 200) AND (a."Cancel" = false)) GROUP BY a."IDNO", a."O_DATE", a."O_RECEIPT", a."O_MONEY" HAVING ((round((a."O_MONEY" * (100)::double precision)) > round(((((-1))::double precision * sum(b."O_MONEY")) * (100)::double precision))) OR (sum(b."O_MONEY") IS NULL)) ORDER BY a."IDNO", a."O_DATE", a."O_RECEIPT";


ALTER TABLE public."VDepositRemain" OWNER TO dev;

--
-- TOC entry 324 (class 1259 OID 65269)
-- Dependencies: 15
-- Name: deposit_before_migrate; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE deposit_before_migrate (
    "IDNO" character varying(25),
    "O_DATE" date,
    "O_RECEIPT" character varying(25),
    "O_MONEY" double precision,
    "O_Type" smallint,
    "O_BANK" character varying(10),
    "O_PRNDATE" date,
    "PayType" character varying(10),
    "Cancel" boolean,
    "O_memo" text,
    "RefAnyID" character varying(12)
);


ALTER TABLE public.deposit_before_migrate OWNER TO dev;

--
-- TOC entry 325 (class 1259 OID 65275)
-- Dependencies: 3097 15
-- Name: VDeposit_before_migrate; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VDeposit_before_migrate" AS
    SELECT deposit_before_migrate."IDNO", sum(deposit_before_migrate."O_MONEY") AS sum FROM deposit_before_migrate WHERE ((deposit_before_migrate."O_Type" = 200) OR (deposit_before_migrate."O_Type" = 299)) GROUP BY deposit_before_migrate."IDNO" ORDER BY deposit_before_migrate."IDNO";


ALTER TABLE public."VDeposit_before_migrate" OWNER TO dev;

--
-- TOC entry 326 (class 1259 OID 65279)
-- Dependencies: 3098 15
-- Name: VDetailCheque; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VDetailCheque" AS
    SELECT "FCheque"."ReceiptDate", "FCheque"."Accept", "DetailCheque"."PostID", "DetailCheque"."ChequeNo", "FCheque"."BankName", "FCheque"."BankBranch", "FCheque"."DateOnCheque", "DetailCheque"."IDNO", "DetailCheque"."TypePay", "DetailCheque"."CusAmount", "FCheque"."DateEnterBank", "FCheque"."IsPass", "FCheque"."IsReturn" FROM ("DetailCheque" LEFT JOIN "FCheque" ON (((("DetailCheque"."PostID")::text = ("FCheque"."PostID")::text) AND (("DetailCheque"."ChequeNo")::text = ("FCheque"."ChequeNo")::text)))) ORDER BY "DetailCheque"."PostID";


ALTER TABLE public."VDetailCheque" OWNER TO dev;

--
-- TOC entry 327 (class 1259 OID 65284)
-- Dependencies: 3099 15
-- Name: VFOtherpayEachDay; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VFOtherpayEachDay" AS
    SELECT "FOtherpay"."O_DATE", "FOtherpay"."O_RECEIPT", "FOtherpay"."IDNO", customer_name(("Fp"."CusID")::text) AS full_name, asset_name(("Fp".asset_type)::integer, ("Fp".asset_id)::text) AS assetname, asset_regis(("Fp".asset_type)::integer, ("Fp".asset_id)::text) AS regis, "TypePay"."TName", "FOtherpay"."O_MONEY", "FOtherpay"."PayType", "FOtherpay"."O_PRNDATE", "FOtherpay"."O_BANK", "FOtherpay"."O_memo" FROM "FOtherpay", "Fp", "TypePay" WHERE (((("FOtherpay"."IDNO")::text = ("Fp"."IDNO")::text) AND ("FOtherpay"."O_Type" = "TypePay"."TypeID")) AND ("FOtherpay"."Cancel" = false)) ORDER BY "FOtherpay"."O_RECEIPT";


ALTER TABLE public."VFOtherpayEachDay" OWNER TO dev;

--
-- TOC entry 328 (class 1259 OID 65289)
-- Dependencies: 3100 15
-- Name: VFrNotPaymentButUseVat; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VFrNotPaymentButUseVat" AS
    SELECT "Fr"."R_Date", "Fr"."R_Receipt", "FVat"."V_Receipt", "Fr"."IDNO", "Fr"."R_Money" AS value, "FVat"."VatValue" AS vat, ("Fr"."R_Money" + "FVat"."VatValue") AS money, "Fr"."R_DueNo", show_type_name(("Fr"."R_DueNo")::integer) AS typepay_name, "Fr"."PayType" FROM "Fr", "Fp", "FVat" WHERE ((((((("Fr"."IDNO")::text = ("Fp"."IDNO")::text) AND (("Fr"."IDNO")::text = ("FVat"."IDNO")::text)) AND ("Fr"."R_DueNo" = "FVat"."V_DueNo")) AND ("Fr"."Cancel" = false)) AND ("FVat"."Cancel" = false)) AND (("Fr"."R_DueNo" = 0) OR ("Fr"."R_DueNo" >= 99))) ORDER BY "Fr"."R_Receipt";


ALTER TABLE public."VFrNotPaymentButUseVat" OWNER TO dev;

--
-- TOC entry 329 (class 1259 OID 65294)
-- Dependencies: 3101 15
-- Name: VNwBillcar; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VNwBillcar" AS
    SELECT c."IDNO", a."IDCarTax", g."TName" AS nameone, c."CusAmt", c."TaxDueDate", e."A_FIRNAME", ((btrim((e."A_NAME")::text) || ' '::text) || btrim((e."A_SIRNAME")::text)) AS full_name, h."C_REGIS" AS carregis, i.car_regis AS gasregis, f."TName" AS nametwo, a."TaxValue", a."CoPayDate", b."RefAnyID", a."TypePay" FROM ((((((((carregis."DetailCarTax" a LEFT JOIN "FOtherpay" b ON (((a."IDCarTax")::text = (b."RefAnyID")::text))) LEFT JOIN carregis."CarTaxDue" c ON (((a."IDCarTax")::text = (c."IDCarTax")::text))) LEFT JOIN "Fp" d ON (((c."IDNO")::text = (d."IDNO")::text))) LEFT JOIN "Fa1" e ON (((d."CusID")::bpchar = e."CusID"))) LEFT JOIN "TypePay" f ON ((a."TypePay" = f."TypeID"))) LEFT JOIN "TypePay" g ON ((c."TypeDep" = g."TypeID"))) LEFT JOIN "Fc" h ON (((d.asset_id)::text = (h."CarID")::text))) LEFT JOIN "FGas" i ON (((d.asset_id)::text = (i."GasID")::text)));


ALTER TABLE public."VNwBillcar" OWNER TO dev;

--
-- TOC entry 330 (class 1259 OID 65299)
-- Dependencies: 3102 15
-- Name: VPostChequeToday; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VPostChequeToday" AS
    SELECT d."PostID", d."ChequeNo", d."IDNO", d."CusAmount", c."AmtOnCheque", c."BankName", c."BankBranch", d."PrnDate" FROM "DetailCheque" d, "FCheque" c WHERE (((((d."PostID")::text = (c."PostID")::text) AND ((d."ChequeNo")::text = (c."ChequeNo")::text)) AND (c."Accept" = true)) AND (c."IsPass" = true));


ALTER TABLE public."VPostChequeToday" OWNER TO dev;

--
-- TOC entry 331 (class 1259 OID 65303)
-- Dependencies: 3103 15
-- Name: VReceiptCashToday; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VReceiptCashToday" AS
    SELECT "PostLog"."PostID", "PostLog"."PostDate", "FCash"."CusID", "FCash"."IDNO", "FCash"."AmtPay", "FCash".refreceipt, ((((btrim(("Fa1"."A_FIRNAME")::text) || ' '::text) || btrim(("Fa1"."A_NAME")::text)) || ' '::text) || btrim(("Fa1"."A_SIRNAME")::text)) AS full_name, "TypePay"."TName" FROM "PostLog", "FCash", "Fa1", "TypePay" WHERE ((((("PostLog"."PostID")::text = ("FCash"."PostID")::text) AND (("FCash"."CusID")::bpchar = "Fa1"."CusID")) AND ("FCash"."TypePay" = "TypePay"."TypeID")) AND ("PostLog"."AcceptPost" = true)) ORDER BY "PostLog"."PostID", "FCash".refreceipt;


ALTER TABLE public."VReceiptCashToday" OWNER TO dev;

--
-- TOC entry 332 (class 1259 OID 65308)
-- Dependencies: 3104 15
-- Name: VRemainPayment; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VRemainPayment" AS
    SELECT "VCusPayment"."IDNO", "VCusPayment"."DueNo", "VCusPayment"."DueDate", (('now'::text)::date - "VCusPayment"."DueDate") AS daydelay, "VCusPayment"."V_Receipt", "VCusPayment"."V_Date" FROM ("VCusPayment" LEFT JOIN "Fp" ON ((("VCusPayment"."IDNO")::text = ("Fp"."IDNO")::text))) WHERE ((("VCusPayment"."DueDate" < ('now'::text)::date) AND ("VCusPayment"."R_Receipt" IS NULL)) AND ("Fp"."P_ACCLOSE" = false)) ORDER BY "VCusPayment"."IDNO", "VCusPayment"."DueDate";


ALTER TABLE public."VRemainPayment" OWNER TO dev;

--
-- TOC entry 4488 (class 0 OID 0)
-- Dependencies: 332
-- Name: VIEW "VRemainPayment"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON VIEW "VRemainPayment" IS 'เป็นรายงานดูค่างวดที่ค้างเดือนที่แล้ว เทียบจากวันที่ปัจจุบันที่ดู  โดยใช้ r_receipt ว่ามีการออกใบเสร็จหรือยัง';


--
-- TOC entry 333 (class 1259 OID 65313)
-- Dependencies: 3105 15
-- Name: VRptSale; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VRptSale" AS
    SELECT "Fp"."P_STDATE", "Fp"."IDNO", customer_name(("Fp"."CusID")::text) AS fullname, asset_name(("Fp".asset_type)::integer, ("Fp".asset_id)::text) AS asset_name, asset_regis(("Fp".asset_type)::integer, ("Fp".asset_id)::text) AS asset_regis, "Fp"."P_DOWN", "Fp"."P_BEGINX", ((round((("Fp"."P_MONTH" * ("Fp"."P_TOTAL")::double precision) * (100)::double precision)) / (100)::double precision) - "Fp"."P_BEGINX") AS intall, (("Fp"."P_MONTH" * ("Fp"."P_TOTAL")::double precision) + "Fp"."P_DOWN") AS hpnonvat, (("Fp"."P_VAT" * ("Fp"."P_TOTAL")::double precision) + "Fp"."P_VatOfDown") AS vatall, ((("Fp"."P_MONTH" * ("Fp"."P_TOTAL")::double precision) + "Fp"."P_DOWN") + (("Fp"."P_VAT" * ("Fp"."P_TOTAL")::double precision) + "Fp"."P_VatOfDown")) AS hpall, "Fp".asset_type FROM "Fp" WHERE ((("Fp"."P_TOTAL" <> 0) AND (("Fp"."IDNO")::text !~~ '%-22___'::text)) AND ((("Fp"."PayType")::text <> 'CC'::text) OR ("Fp"."PayType" IS NULL))) ORDER BY "Fp"."P_STDATE";


ALTER TABLE public."VRptSale" OWNER TO dev;

--
-- TOC entry 334 (class 1259 OID 65318)
-- Dependencies: 3106 15
-- Name: VRptVat; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VRptVat" AS
    SELECT "FVat"."V_Date", "FVat"."V_DueNo", "FVat"."V_Receipt", "FVat"."IDNO", "Fp"."CusID", "Fp".asset_type, "Fp".asset_id, "FVat"."VatValue", "Fr"."R_Date", "Fr"."R_Receipt" FROM (("FVat" LEFT JOIN "Fp" ON ((("FVat"."IDNO")::text = ("Fp"."IDNO")::text))) LEFT JOIN "Fr" ON ((((("FVat"."IDNO")::text = ("Fr"."IDNO")::text) AND (("FVat"."V_DueNo")::text = ("Fr"."R_DueNo")::text)) AND ("Fr"."Cancel" = false)))) WHERE ("FVat"."Cancel" = false) ORDER BY "FVat"."V_Receipt", "FVat"."V_DueNo";


ALTER TABLE public."VRptVat" OWNER TO dev;

--
-- TOC entry 335 (class 1259 OID 65323)
-- Dependencies: 3107 15
-- Name: VSearchCus; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VSearchCus" AS
    SELECT (((btrim(("Fa1"."A_FIRNAME")::text) || btrim(("Fa1"."A_NAME")::text)) || ' '::text) || btrim(("Fa1"."A_SIRNAME")::text)) AS full_name, "Fa1"."CusID" FROM "Fa1";


ALTER TABLE public."VSearchCus" OWNER TO dev;

--
-- TOC entry 4489 (class 0 OID 0)
-- Dependencies: 335
-- Name: VIEW "VSearchCus"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON VIEW "VSearchCus" IS 'สำหรับค้นหาชื่อลูกค้า';


--
-- TOC entry 336 (class 1259 OID 65327)
-- Dependencies: 3108 15
-- Name: VSearchCusTemp; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VSearchCusTemp" AS
    SELECT (((btrim(("Customer_Temp"."A_FIRNAME")::text) || btrim(("Customer_Temp"."A_NAME")::text)) || ' '::text) || btrim(("Customer_Temp"."A_SIRNAME")::text)) AS full_name, "Customer_Temp"."CusID" FROM "Customer_Temp";


ALTER TABLE public."VSearchCusTemp" OWNER TO dev;

--
-- TOC entry 4490 (class 0 OID 0)
-- Dependencies: 336
-- Name: VIEW "VSearchCusTemp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON VIEW "VSearchCusTemp" IS 'สำหรับค้นหาชื่อลูกค้า';


--
-- TOC entry 337 (class 1259 OID 65331)
-- Dependencies: 3109 15
-- Name: VTRAccNotKnow; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VTRAccNotKnow" AS
    SELECT "TranPay".tr_date, ('a'::text || NULL::text) AS a, ('b'::text || NULL::text) AS b, "TranPay".ref_name, ('c'::text || NULL::text) AS c, ('d'::text || NULL::text) AS d, ('f'::text || NULL::text) AS f, bank_code(("TranPay".bank_no)::text) AS bank_no, "TranPay".tr_date AS g, ('h'::text || NULL::text) AS h, "TranPay".amt, "TranPay".terminal_id FROM "TranPay" WHERE ("TranPay".post_on_asa_sys = false);


ALTER TABLE public."VTRAccNotKnow" OWNER TO dev;

--
-- TOC entry 338 (class 1259 OID 65335)
-- Dependencies: 3110 15
-- Name: VTranPay; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VTranPay" AS
    SELECT "DetailTranpay"."PostID", "DetailTranpay"."IDNO", COALESCE("VFrEachDay".full_name, "VFOtherpayEachDay".full_name) AS name, COALESCE("VFrEachDay"."R_Date", "VFOtherpayEachDay"."O_DATE") AS rec_date, "DetailTranpay"."ReceiptNo", COALESCE("VFrEachDay".money, "VFOtherpayEachDay"."O_MONEY") AS amount, "DetailTranpay"."TypePay", COALESCE("VFrEachDay"."PayType", "VFOtherpayEachDay"."PayType") AS bank, COALESCE("VFrEachDay"."R_memo", "VFOtherpayEachDay"."O_memo") AS memo FROM (("DetailTranpay" LEFT JOIN "VFrEachDay" ON ((("DetailTranpay"."ReceiptNo")::text = ("VFrEachDay"."R_Receipt")::text))) LEFT JOIN "VFOtherpayEachDay" ON ((("DetailTranpay"."ReceiptNo")::text = ("VFOtherpayEachDay"."O_RECEIPT")::text))) ORDER BY "DetailTranpay"."PostID";


ALTER TABLE public."VTranPay" OWNER TO dev;

--
-- TOC entry 339 (class 1259 OID 65340)
-- Dependencies: 3111 15
-- Name: VUserAcceptCash; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VUserAcceptCash" AS
    SELECT c."PostID", c."CusID", c."IDNO", c."TypePay", c."AmtPay", c.refreceipt, l."UserIDPost", l."UserIDAccept", l."PostDate", l.paytype FROM "FCash" c, "PostLog" l WHERE (((c."PostID")::text = (l."PostID")::text) AND (l."AcceptPost" = true)) ORDER BY l."UserIDAccept";


ALTER TABLE public."VUserAcceptCash" OWNER TO dev;

--
-- TOC entry 340 (class 1259 OID 65344)
-- Dependencies: 3318 15
-- Name: fuser; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE fuser (
    fname character varying(100),
    username character varying(30),
    password character varying(32),
    office_id smallint DEFAULT 0 NOT NULL,
    user_group character varying(3),
    id_user character varying(12) NOT NULL,
    status_user boolean,
    last_log timestamp without time zone,
    last_datepassword timestamp without time zone,
    user_dep character varying(10),
    title character varying(50),
    lname character varying(80)
);


ALTER TABLE public.fuser OWNER TO dev;

--
-- TOC entry 4491 (class 0 OID 0)
-- Dependencies: 340
-- Name: COLUMN fuser.title; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser.title IS 'คำนำหน้าชื่อ';


--
-- TOC entry 4492 (class 0 OID 0)
-- Dependencies: 340
-- Name: COLUMN fuser.lname; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser.lname IS 'นามสกุล';


--
-- TOC entry 341 (class 1259 OID 65348)
-- Dependencies: 3112 15
-- Name: VUserReceiptCash; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "VUserReceiptCash" AS
    SELECT p."PostDate", p."UserIDAccept", u.fname AS fullname, c."CusID", a1."A_NAME", a1."A_SIRNAME", c."IDNO", c."TypePay", t."TName", c."AmtPay", c.refreceipt, p."PostTime" FROM "FCash" c, "PostLog" p, "Fa1" a1, fuser u, "TypePay" t WHERE (((((((c."PostID")::text = (p."PostID")::text) AND ((c."CusID")::bpchar = a1."CusID")) AND ((p."UserIDAccept")::text = (u.id_user)::text)) AND (c."TypePay" = t."TypeID")) AND ((p.paytype)::text = 'CA'::text)) AND (c.cancel = false)) ORDER BY p."PostDate", p."UserIDAccept", c.refreceipt;


ALTER TABLE public."VUserReceiptCash" OWNER TO dev;

--
-- TOC entry 342 (class 1259 OID 65353)
-- Dependencies: 3113 15
-- Name: Vfuser; Type: VIEW; Schema: public; Owner: dev
--

CREATE VIEW "Vfuser" AS
    SELECT fuser.id_user, pg_catalog.concat(COALESCE(fuser.title, ''::character varying), COALESCE(fuser.fname, ''::character varying), ' ', COALESCE(fuser.lname, ''::character varying)) AS fullname, fuser.username, fuser.password, fuser.office_id, fuser.user_group, fuser.status_user, fuser.last_log, fuser.last_datepassword, fuser.user_dep FROM fuser;


ALTER TABLE public."Vfuser" OWNER TO dev;

--
-- TOC entry 4493 (class 0 OID 0)
-- Dependencies: 342
-- Name: VIEW "Vfuser"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON VIEW "Vfuser" IS 'เลียนแบบตาราง fuser';


--
-- TOC entry 343 (class 1259 OID 65357)
-- Dependencies: 15
-- Name: bankofcompany; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE bankofcompany (
    accno character varying(20) NOT NULL,
    bankname character varying(25),
    bankbranch character varying(30),
    payment boolean,
    bankno character varying(10)
);


ALTER TABLE public.bankofcompany OWNER TO dev;

--
-- TOC entry 344 (class 1259 OID 65360)
-- Dependencies: 15
-- Name: branch; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE branch (
    brn_id smallint NOT NULL,
    brn_name character varying(50),
    brn_ads text
);


ALTER TABLE public.branch OWNER TO dev;

--
-- TOC entry 345 (class 1259 OID 65366)
-- Dependencies: 15
-- Name: department; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE department (
    dep_id character varying(10) NOT NULL,
    dep_name character varying(50)
);


ALTER TABLE public.department OWNER TO dev;

--
-- TOC entry 346 (class 1259 OID 65369)
-- Dependencies: 15
-- Name: f_department; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE f_department (
    fdep_id character varying(10) NOT NULL,
    fdep_name character varying(50),
    fstatus boolean
);


ALTER TABLE public.f_department OWNER TO dev;

--
-- TOC entry 4494 (class 0 OID 0)
-- Dependencies: 346
-- Name: TABLE f_department; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE f_department IS 'ตารางเก็บ ฝ่าย หรือ กลุ่มผู้ใช้';


--
-- TOC entry 4495 (class 0 OID 0)
-- Dependencies: 346
-- Name: COLUMN f_department.fdep_id; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN f_department.fdep_id IS 'รหัสฝ่าย';


--
-- TOC entry 4496 (class 0 OID 0)
-- Dependencies: 346
-- Name: COLUMN f_department.fdep_name; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN f_department.fdep_name IS 'ชื่อฝ่าย';


--
-- TOC entry 4497 (class 0 OID 0)
-- Dependencies: 346
-- Name: COLUMN f_department.fstatus; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN f_department.fstatus IS 'สถานะการใช้งาน
f=ระงับการใช้งาน
t=ใช้งาน';


--
-- TOC entry 347 (class 1259 OID 65372)
-- Dependencies: 15
-- Name: f_groupuser; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE f_groupuser (
    id_qroup character varying(3) NOT NULL,
    name_group character varying(50)
);


ALTER TABLE public.f_groupuser OWNER TO dev;

--
-- TOC entry 348 (class 1259 OID 65375)
-- Dependencies: 15
-- Name: f_menu; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE f_menu (
    id_menu character varying(5) NOT NULL,
    name_menu character varying(50),
    status_menu character varying(3),
    path_menu character varying(50)
);


ALTER TABLE public.f_menu OWNER TO dev;

--
-- TOC entry 349 (class 1259 OID 65378)
-- Dependencies: 3319 15
-- Name: f_usermenu; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE f_usermenu (
    id_menu character varying(10) NOT NULL,
    id_user character varying(12) NOT NULL,
    status boolean DEFAULT true NOT NULL
);


ALTER TABLE public.f_usermenu OWNER TO dev;

--
-- TOC entry 4498 (class 0 OID 0)
-- Dependencies: 349
-- Name: COLUMN f_usermenu.status; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN f_usermenu.status IS 'สถานะของ user สามารถใช้ page  id_menu ได้หรือไม่';


--
-- TOC entry 350 (class 1259 OID 65382)
-- Dependencies: 15
-- Name: fuser_detail; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE fuser_detail (
    id_user character varying(12) NOT NULL,
    title_eng character varying(15),
    fname_eng character varying(50),
    lname_eng character varying(50),
    nickname character varying(25),
    u_birthday date,
    u_status character varying(20),
    u_sex character varying(10),
    u_idnum character varying(13),
    u_pic character varying(100),
    u_pos character varying(100),
    u_salary double precision,
    u_tel character varying(20),
    u_extens character varying(10),
    u_email character varying(50),
    startwork date,
    user_keylast character varying(12),
    keydatelast date
);


ALTER TABLE public.fuser_detail OWNER TO dev;

--
-- TOC entry 4499 (class 0 OID 0)
-- Dependencies: 350
-- Name: TABLE fuser_detail; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE fuser_detail IS 'ตารางเก็บข้อมูลเพิ่มเติมของพนักงาน';


--
-- TOC entry 4500 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.id_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.id_user IS 'รหัสพนักงาน';


--
-- TOC entry 4501 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.title_eng; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.title_eng IS 'คำนำหน้าภาษาอังกฤษ';


--
-- TOC entry 4502 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.fname_eng; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.fname_eng IS 'ชื่อภาษาอังกฤษ';


--
-- TOC entry 4503 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.lname_eng; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.lname_eng IS 'นามสกุลภาษาอังกฤษ';


--
-- TOC entry 4504 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.nickname; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.nickname IS 'ชื่อเล่นภาษาไทย';


--
-- TOC entry 4505 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_birthday; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_birthday IS 'วันเกิด';


--
-- TOC entry 4506 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_status; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_status IS 'สถานภาพ';


--
-- TOC entry 4507 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_sex; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_sex IS 'เพศ';


--
-- TOC entry 4508 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_idnum; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_idnum IS 'เลขบัตรประชาชน';


--
-- TOC entry 4509 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_pic; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_pic IS 'รูปหน้า';


--
-- TOC entry 4510 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_pos; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_pos IS 'ตำแหน่ง';


--
-- TOC entry 4511 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_salary; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_salary IS 'เงินเดือน';


--
-- TOC entry 4512 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_tel; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_tel IS 'เบอร์โทรศัพท์';


--
-- TOC entry 4513 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_extens; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_extens IS 'เบอร์ต่อที่โต๊ะ';


--
-- TOC entry 4514 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.u_email; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.u_email IS 'E-mail';


--
-- TOC entry 4515 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.startwork; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.startwork IS 'วันที่เริ่มทำงาน';


--
-- TOC entry 4516 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.user_keylast; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.user_keylast IS 'พนักงานที่บันทึกหรือแก้ไขข้อมูลคนสุดท้าย';


--
-- TOC entry 4517 (class 0 OID 0)
-- Dependencies: 350
-- Name: COLUMN fuser_detail.keydatelast; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN fuser_detail.keydatelast IS 'วันที่บันทึกหรือแก้ไขล่าสุด';


--
-- TOC entry 351 (class 1259 OID 65388)
-- Dependencies: 3320 3321 15
-- Name: logs_NTDetail; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "logs_NTDetail" (
    autoid integer NOT NULL,
    "NTID" character varying(12),
    "Detail" character varying(200),
    "Amount" double precision DEFAULT 0,
    "MainDetail" boolean DEFAULT true,
    "keyDate" timestamp without time zone,
    id_user character varying(12)
);


ALTER TABLE public."logs_NTDetail" OWNER TO dev;

--
-- TOC entry 352 (class 1259 OID 65393)
-- Dependencies: 351 15
-- Name: logs_NTDetail_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "logs_NTDetail_autoid_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."logs_NTDetail_autoid_seq" OWNER TO dev;

--
-- TOC entry 4518 (class 0 OID 0)
-- Dependencies: 352
-- Name: logs_NTDetail_autoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "logs_NTDetail_autoid_seq" OWNED BY "logs_NTDetail".autoid;


--
-- TOC entry 353 (class 1259 OID 65395)
-- Dependencies: 15
-- Name: logs_nw_login; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE logs_nw_login (
    logid integer NOT NULL,
    username character varying(20),
    "loginDate" date,
    "loginTime" character varying(10),
    cancel boolean
);


ALTER TABLE public.logs_nw_login OWNER TO dev;

--
-- TOC entry 4519 (class 0 OID 0)
-- Dependencies: 353
-- Name: TABLE logs_nw_login; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE logs_nw_login IS 'เก็บ logs ของการ login ผิด';


--
-- TOC entry 4520 (class 0 OID 0)
-- Dependencies: 353
-- Name: COLUMN logs_nw_login.username; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN logs_nw_login.username IS 'username ที่เข้าระบบ เก็บเฉพาะที่มี user ในฐานข้อมูล';


--
-- TOC entry 4521 (class 0 OID 0)
-- Dependencies: 353
-- Name: COLUMN logs_nw_login."loginDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN logs_nw_login."loginDate" IS 'วันที่ login';


--
-- TOC entry 354 (class 1259 OID 65398)
-- Dependencies: 15
-- Name: nw_logsregis_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE nw_logsregis_autoid_seq
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nw_logsregis_autoid_seq OWNER TO dev;

--
-- TOC entry 355 (class 1259 OID 65400)
-- Dependencies: 3323 15
-- Name: logs_nw_regis; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE logs_nw_regis (
    "logsID" integer DEFAULT nextval('nw_logsregis_autoid_seq'::regclass) NOT NULL,
    "IDNO" character varying(25),
    asset_id character varying(12),
    fieldedit character varying(80),
    data_old character varying(50),
    data_new character varying(50),
    datekey timestamp without time zone,
    id_user character varying(12)
);


ALTER TABLE public.logs_nw_regis OWNER TO dev;

--
-- TOC entry 4522 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN logs_nw_regis."logsID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN logs_nw_regis."logsID" IS 'pk ของตาราง';


--
-- TOC entry 4523 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN logs_nw_regis."IDNO"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN logs_nw_regis."IDNO" IS 'เลขที่สัญญา';


--
-- TOC entry 4524 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN logs_nw_regis.fieldedit; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN logs_nw_regis.fieldedit IS 'ฟิล์ที่แก้ไขข้อมูล';


--
-- TOC entry 4525 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN logs_nw_regis.data_old; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN logs_nw_regis.data_old IS 'ข้อมูลเก่า';


--
-- TOC entry 4526 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN logs_nw_regis.data_new; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN logs_nw_regis.data_new IS 'ข้อมูลใหม่';


--
-- TOC entry 4527 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN logs_nw_regis.datekey; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN logs_nw_regis.datekey IS 'วันที่แก้ไขข้อมูล';


--
-- TOC entry 4528 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN logs_nw_regis.id_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN logs_nw_regis.id_user IS 'รหัสพนักงานที่แก้ไขข้อมูล';


--
-- TOC entry 356 (class 1259 OID 65404)
-- Dependencies: 15
-- Name: nw_annoucefile; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_annoucefile (
    "annfileId" integer NOT NULL,
    "annId" integer,
    pathfile character varying(200)
);


ALTER TABLE public.nw_annoucefile OWNER TO dev;

--
-- TOC entry 4529 (class 0 OID 0)
-- Dependencies: 356
-- Name: TABLE nw_annoucefile; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE nw_annoucefile IS 'ตารางเก็บไฟล์ที่ upload ประกาศ';


--
-- TOC entry 4530 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN nw_annoucefile."annfileId"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucefile."annfileId" IS 'รหัสไฟล์ที่ทำการ upload';


--
-- TOC entry 4531 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN nw_annoucefile."annId"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucefile."annId" IS 'รหัส Annoucement ';


--
-- TOC entry 4532 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN nw_annoucefile.pathfile; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucefile.pathfile IS 'เก็บ path เพื่ออ้างอิงไปยังที่เก็บไฟล์';


--
-- TOC entry 357 (class 1259 OID 65407)
-- Dependencies: 15
-- Name: nw_annoucement; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_annoucement (
    "annId" integer NOT NULL,
    "typeAnnId" integer,
    "annTitle" text,
    "annContent" text,
    "annAuthor" character varying(12),
    "keyDate" date,
    "statusApprove" boolean,
    "annApprove" character varying(12),
    "approveDate" date
);


ALTER TABLE public.nw_annoucement OWNER TO dev;

--
-- TOC entry 4533 (class 0 OID 0)
-- Dependencies: 357
-- Name: TABLE nw_annoucement; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE nw_annoucement IS 'ตารางเก็บรายละเอียดของ Annoucement';


--
-- TOC entry 4534 (class 0 OID 0)
-- Dependencies: 357
-- Name: COLUMN nw_annoucement."annId"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucement."annId" IS 'รหัส Annoucement';


--
-- TOC entry 4535 (class 0 OID 0)
-- Dependencies: 357
-- Name: COLUMN nw_annoucement."typeAnnId"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucement."typeAnnId" IS 'รหัสประเภท annoucement';


--
-- TOC entry 4536 (class 0 OID 0)
-- Dependencies: 357
-- Name: COLUMN nw_annoucement."annTitle"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucement."annTitle" IS 'หัวเรื่อง';


--
-- TOC entry 4537 (class 0 OID 0)
-- Dependencies: 357
-- Name: COLUMN nw_annoucement."annContent"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucement."annContent" IS 'ข้อความ/เนื้อหา';


--
-- TOC entry 4538 (class 0 OID 0)
-- Dependencies: 357
-- Name: COLUMN nw_annoucement."annAuthor"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucement."annAuthor" IS 'รหัสของ user ที่ตั้งเรื่อง';


--
-- TOC entry 4539 (class 0 OID 0)
-- Dependencies: 357
-- Name: COLUMN nw_annoucement."keyDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucement."keyDate" IS 'วันที่ตั้งเรื่อง';


--
-- TOC entry 4540 (class 0 OID 0)
-- Dependencies: 357
-- Name: COLUMN nw_annoucement."statusApprove"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucement."statusApprove" IS 'สถานะการ approve
t= approve แล้ว
f= ยังไม่ approve';


--
-- TOC entry 4541 (class 0 OID 0)
-- Dependencies: 357
-- Name: COLUMN nw_annoucement."annApprove"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucement."annApprove" IS 'รหัสของ user ที่ทำการ approve';


--
-- TOC entry 4542 (class 0 OID 0)
-- Dependencies: 357
-- Name: COLUMN nw_annoucement."approveDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucement."approveDate" IS 'วันที่ approve';


--
-- TOC entry 358 (class 1259 OID 65413)
-- Dependencies: 15
-- Name: nw_annoucetype; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_annoucetype (
    "typeAnnId" integer NOT NULL,
    "typeAnnName" character varying(200),
    "typeStatusUse" boolean
);


ALTER TABLE public.nw_annoucetype OWNER TO dev;

--
-- TOC entry 4543 (class 0 OID 0)
-- Dependencies: 358
-- Name: TABLE nw_annoucetype; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE nw_annoucetype IS 'ตารางตั้งค่า annoucetype';


--
-- TOC entry 4544 (class 0 OID 0)
-- Dependencies: 358
-- Name: COLUMN nw_annoucetype."typeAnnId"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucetype."typeAnnId" IS 'รหัสของประเภท annoucement';


--
-- TOC entry 4545 (class 0 OID 0)
-- Dependencies: 358
-- Name: COLUMN nw_annoucetype."typeAnnName"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucetype."typeAnnName" IS 'ชื่อประเภท Annoucement';


--
-- TOC entry 4546 (class 0 OID 0)
-- Dependencies: 358
-- Name: COLUMN nw_annoucetype."typeStatusUse"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annoucetype."typeStatusUse" IS 'สถานะการเปิดใช้ประเภท
t=เปิดใช้
f=ไม่ใช้';


--
-- TOC entry 359 (class 1259 OID 65416)
-- Dependencies: 15
-- Name: nw_annouceuser_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE nw_annouceuser_autoid_seq
    START WITH 1
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nw_annouceuser_autoid_seq OWNER TO dev;

--
-- TOC entry 4547 (class 0 OID 0)
-- Dependencies: 359
-- Name: SEQUENCE nw_annouceuser_autoid_seq; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON SEQUENCE nw_annouceuser_autoid_seq IS ' run id ของตาราง nw_annouceuser';


--
-- TOC entry 360 (class 1259 OID 65418)
-- Dependencies: 3324 15
-- Name: nw_annouceuser; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_annouceuser (
    "annuserId" integer DEFAULT nextval('nw_annouceuser_autoid_seq'::regclass) NOT NULL,
    id_user character varying(12),
    "annId" integer,
    "statusAccept" character varying(1),
    accepted_stamp timestamp without time zone
);


ALTER TABLE public.nw_annouceuser OWNER TO dev;

--
-- TOC entry 4548 (class 0 OID 0)
-- Dependencies: 360
-- Name: TABLE nw_annouceuser; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE nw_annouceuser IS 'ตารางเก็บ user ที่ต้องการให้แสดง annoucement';


--
-- TOC entry 4549 (class 0 OID 0)
-- Dependencies: 360
-- Name: COLUMN nw_annouceuser."annuserId"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annouceuser."annuserId" IS 'รหัสของตาราง';


--
-- TOC entry 4550 (class 0 OID 0)
-- Dependencies: 360
-- Name: COLUMN nw_annouceuser.id_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annouceuser.id_user IS 'รหัสของพนักงานที่ต้องการให้แสดง annoucement';


--
-- TOC entry 4551 (class 0 OID 0)
-- Dependencies: 360
-- Name: COLUMN nw_annouceuser."annId"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annouceuser."annId" IS 'รหัสของ annoucement ที่ต้องการให้แสดงในแต่ละ user ';


--
-- TOC entry 4552 (class 0 OID 0)
-- Dependencies: 360
-- Name: COLUMN nw_annouceuser."statusAccept"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_annouceuser."statusAccept" IS 'สถานะการรับทราบข่าวสารของ user

0=ไม่อนุญาตให้แสดงประกาศ
1=อนุญาตให้แสดงประกาศ
2= รับทราบแล้ว';


--
-- TOC entry 361 (class 1259 OID 65422)
-- Dependencies: 15
-- Name: nw_changemenu_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE nw_changemenu_autoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nw_changemenu_autoid_seq OWNER TO dev;

--
-- TOC entry 4553 (class 0 OID 0)
-- Dependencies: 361
-- Name: SEQUENCE nw_changemenu_autoid_seq; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON SEQUENCE nw_changemenu_autoid_seq IS 'ใช้สำหรับ run changeID ในตาราง nw_changemenu';


--
-- TOC entry 362 (class 1259 OID 65424)
-- Dependencies: 3325 15
-- Name: nw_changemenu; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_changemenu (
    "changeID" integer DEFAULT nextval('nw_changemenu_autoid_seq'::regclass) NOT NULL,
    id_menu character varying(10),
    id_user character varying(12),
    status boolean,
    result text,
    add_user character(12),
    add_date date,
    approve_user character varying(12),
    approve_date date,
    "statusApprove" smallint,
    "statusOKapprove" boolean
);


ALTER TABLE public.nw_changemenu OWNER TO dev;

--
-- TOC entry 4554 (class 0 OID 0)
-- Dependencies: 362
-- Name: TABLE nw_changemenu; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE nw_changemenu IS 'ตารางเก็บเมนูที่เปลี่ยนแปลงไป';


--
-- TOC entry 4555 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu."changeID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu."changeID" IS 'PK ของตาราง';


--
-- TOC entry 4556 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu.id_menu; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu.id_menu IS 'เมนูที่เพิ่มหรือเปลี่ยนแปลง';


--
-- TOC entry 4557 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu.id_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu.id_user IS 'user ที่เปลี่ยนแปลงเมนู';


--
-- TOC entry 4558 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu.status; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu.status IS 'สถานะการอนุญาตให้ใช้เมนู';


--
-- TOC entry 4559 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu.result; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu.result IS 'เหตุผลในการเปลี่ยนแปลงเมนู';


--
-- TOC entry 4560 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu.add_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu.add_user IS 'user ที่เป็นคนเพิ่มหรือเปลี่ยนแปลงเมนู';


--
-- TOC entry 4561 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu.add_date; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu.add_date IS 'วันที่เพิ่มหรือเปลี่ยนแปลงเมนู';


--
-- TOC entry 4562 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu.approve_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu.approve_user IS 'user ที่ทำการอนุมัติ';


--
-- TOC entry 4563 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu.approve_date; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu.approve_date IS 'วันที่อนุมัติเมนู';


--
-- TOC entry 4564 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu."statusApprove"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu."statusApprove" IS 'สถานะในการอนุมัติ
0=รออนุมัติ
1=ยกเลิกรายการ
2=อนุมัติ
3=ไม่อนุมัติ
4=รับทราบการอนุมัติ';


--
-- TOC entry 4565 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN nw_changemenu."statusOKapprove"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_changemenu."statusOKapprove" IS 'สถานะในการรับทราบการอนุมัติ
f=ยังไม่รับทราบ
t=รับทราบแล้ว';


--
-- TOC entry 363 (class 1259 OID 65431)
-- Dependencies: 15
-- Name: nw_createVIP; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "nw_createVIP" (
    "IDNO" character varying(25) NOT NULL,
    id_user character varying(12),
    "createDate" timestamp without time zone
);


ALTER TABLE public."nw_createVIP" OWNER TO dev;

--
-- TOC entry 4566 (class 0 OID 0)
-- Dependencies: 363
-- Name: COLUMN "nw_createVIP"."IDNO"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_createVIP"."IDNO" IS 'เลขที่สัญญ VIP';


--
-- TOC entry 4567 (class 0 OID 0)
-- Dependencies: 363
-- Name: COLUMN "nw_createVIP".id_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_createVIP".id_user IS 'รหัสพนักงานที่สร้างรายการ';


--
-- TOC entry 4568 (class 0 OID 0)
-- Dependencies: 363
-- Name: COLUMN "nw_createVIP"."createDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_createVIP"."createDate" IS 'วันที่สร้างรายการ';


--
-- TOC entry 364 (class 1259 OID 65434)
-- Dependencies: 15
-- Name: nw_credit; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_credit (
    "creditID" character varying(25) NOT NULL,
    "creditType" character varying(255),
    "creditReserved" character varying(255),
    "creditDetail" text,
    "statusUse" boolean,
    "createDate" timestamp without time zone,
    id_user character varying(12)
);


ALTER TABLE public.nw_credit OWNER TO dev;

--
-- TOC entry 4569 (class 0 OID 0)
-- Dependencies: 364
-- Name: TABLE nw_credit; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE nw_credit IS 'ตารางสำหรับเก็บประเภทสินเชื่อ';


--
-- TOC entry 4570 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN nw_credit."creditID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_credit."creditID" IS 'รหัสประเภทสินเชื่อ';


--
-- TOC entry 4571 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN nw_credit."creditType"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_credit."creditType" IS 'ประเภทสินเชื่อ';


--
-- TOC entry 4572 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN nw_credit."creditReserved"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_credit."creditReserved" IS 'Field สำรอง';


--
-- TOC entry 4573 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN nw_credit."creditDetail"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_credit."creditDetail" IS 'คำอธิบายรายละเอียด';


--
-- TOC entry 4574 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN nw_credit."statusUse"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_credit."statusUse" IS 'สถานะการเปิดใช้งาน
T=เปิดใช้งาน
F=ไม่เปิดใช้งาน';


--
-- TOC entry 4575 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN nw_credit."createDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_credit."createDate" IS 'วันที่สร้างรายการ';


--
-- TOC entry 4576 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN nw_credit.id_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_credit.id_user IS 'ผู้ที่สร้างรายการ';


--
-- TOC entry 365 (class 1259 OID 65440)
-- Dependencies: 15
-- Name: nw_organize_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE nw_organize_autoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nw_organize_autoid_seq OWNER TO dev;

--
-- TOC entry 366 (class 1259 OID 65442)
-- Dependencies: 3326 15
-- Name: nw_organize; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_organize (
    "organizeID" integer DEFAULT nextval('nw_organize_autoid_seq'::regclass) NOT NULL,
    organize_name character varying(80)
);


ALTER TABLE public.nw_organize OWNER TO dev;

--
-- TOC entry 367 (class 1259 OID 65446)
-- Dependencies: 15
-- Name: nw_province; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_province (
    "proName" character varying(50),
    "proID" character(2) NOT NULL
);


ALTER TABLE public.nw_province OWNER TO dev;

--
-- TOC entry 4577 (class 0 OID 0)
-- Dependencies: 367
-- Name: COLUMN nw_province."proName"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_province."proName" IS 'ชื่อจังหวัด';


--
-- TOC entry 368 (class 1259 OID 65449)
-- Dependencies: 15
-- Name: seize_car_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE seize_car_autoid_seq
    START WITH 12
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seize_car_autoid_seq OWNER TO dev;

--
-- TOC entry 369 (class 1259 OID 65451)
-- Dependencies: 3327 15
-- Name: nw_seize_car; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_seize_car (
    "seizeID" integer DEFAULT nextval('seize_car_autoid_seq'::regclass) NOT NULL,
    "IDNO" character varying(25),
    seize_user character varying(12),
    yellow_date date,
    seize_result text,
    send_user character varying(12),
    send_date date,
    status_approve integer,
    approve_user character varying(12),
    approve_date date,
    authorize_user character varying(12),
    witness_user1 character varying(12),
    witness_user2 character varying(12),
    "NTID" character varying(12),
    "organizeID" integer,
    proxy_usersend character varying(12),
    proxy_datesend date
);


ALTER TABLE public.nw_seize_car OWNER TO dev;

--
-- TOC entry 4578 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.seize_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.seize_user IS 'พนักงานที่ทำการยึดรถ';


--
-- TOC entry 4579 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.yellow_date; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.yellow_date IS 'วันที่ได้รับใบเหลือง';


--
-- TOC entry 4580 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.seize_result; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.seize_result IS 'เหตุผลในการยึดรถ';


--
-- TOC entry 4581 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.send_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.send_user IS 'พนักงานที่ส่งเรื่องยึดรถ';


--
-- TOC entry 4582 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.send_date; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.send_date IS 'วันที่ส่งเรื่องยึดรถ';


--
-- TOC entry 4583 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.status_approve; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.status_approve IS 'สถานะในการยึดรถ
1 = รออนุมัติ
2 = รอแจ้งงาน
3 = อยู่ระหว่างยึด
4 = ยึดรถเข้ามาแล้ว';


--
-- TOC entry 4584 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.approve_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.approve_user IS 'พนักงานที่อนุมัติ';


--
-- TOC entry 4585 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.approve_date; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.approve_date IS 'วันที่อนุมัติ';


--
-- TOC entry 4586 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.authorize_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.authorize_user IS 'ผู้รับมอบอำนาจ';


--
-- TOC entry 4587 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.witness_user1; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.witness_user1 IS 'พยานคนที่ 1';


--
-- TOC entry 4588 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.witness_user2; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.witness_user2 IS 'พยานคนที่ 2';


--
-- TOC entry 4589 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.proxy_usersend; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.proxy_usersend IS 'ผู้ส่งหนังสือมอบอำนาจ';


--
-- TOC entry 4590 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN nw_seize_car.proxy_datesend; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_seize_car.proxy_datesend IS 'วันที่ส่งหนังสือมอบอำนาจ';


--
-- TOC entry 370 (class 1259 OID 65458)
-- Dependencies: 15
-- Name: nw_startDateFp; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "nw_startDateFp" (
    "IDNO" character varying(25) NOT NULL,
    id_user character varying(12),
    "startDate" timestamp without time zone
);


ALTER TABLE public."nw_startDateFp" OWNER TO dev;

--
-- TOC entry 4591 (class 0 OID 0)
-- Dependencies: 370
-- Name: COLUMN "nw_startDateFp"."IDNO"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_startDateFp"."IDNO" IS 'เลขที่สัญญา';


--
-- TOC entry 4592 (class 0 OID 0)
-- Dependencies: 370
-- Name: COLUMN "nw_startDateFp".id_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_startDateFp".id_user IS 'พนักงานที่ทำรายการเช่าซื้อ';


--
-- TOC entry 4593 (class 0 OID 0)
-- Dependencies: 370
-- Name: COLUMN "nw_startDateFp"."startDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_startDateFp"."startDate" IS 'วันที่และเวลาที่ทำรายการ';


--
-- TOC entry 371 (class 1259 OID 65461)
-- Dependencies: 15
-- Name: nw_statusnt_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE nw_statusnt_autoid_seq
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nw_statusnt_autoid_seq OWNER TO dev;

--
-- TOC entry 372 (class 1259 OID 65463)
-- Dependencies: 3328 15
-- Name: nw_statusNT; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "nw_statusNT" (
    "statusID" integer DEFAULT nextval('nw_statusnt_autoid_seq'::regclass) NOT NULL,
    "NTID" character varying(12),
    "IDNO" character varying(25),
    "statusNT" smallint,
    result_noapprove text,
    user_approve character varying(12),
    date_approve timestamp without time zone
);


ALTER TABLE public."nw_statusNT" OWNER TO dev;

--
-- TOC entry 4594 (class 0 OID 0)
-- Dependencies: 372
-- Name: COLUMN "nw_statusNT"."statusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_statusNT"."statusID" IS 'pk หลักของตาราง';


--
-- TOC entry 4595 (class 0 OID 0)
-- Dependencies: 372
-- Name: COLUMN "nw_statusNT"."NTID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_statusNT"."NTID" IS 'NTID ที่ผ่านการอนุมัติหรือไม่อนุมัติ';


--
-- TOC entry 4596 (class 0 OID 0)
-- Dependencies: 372
-- Name: COLUMN "nw_statusNT"."IDNO"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_statusNT"."IDNO" IS 'เลขที่สัญญา';


--
-- TOC entry 4597 (class 0 OID 0)
-- Dependencies: 372
-- Name: COLUMN "nw_statusNT"."statusNT"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_statusNT"."statusNT" IS 'สถานะ NT
1=อนุมัติ
2=ไม่อนุมัติ
3=ส่งจดหมายครบแล้ว
4=ส่งจดหมายแล้วแต่ยังไม่ครบ
5=รออนุมัติยกเลิก(กรณีจ่ายเงินแล้ว)
6=ยกเลิก NT แล้ว(กรณีจ่ายเงินแล้ว)';


--
-- TOC entry 4598 (class 0 OID 0)
-- Dependencies: 372
-- Name: COLUMN "nw_statusNT".result_noapprove; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_statusNT".result_noapprove IS 'เหตุผลที่ไม่อนุมัติ';


--
-- TOC entry 4599 (class 0 OID 0)
-- Dependencies: 372
-- Name: COLUMN "nw_statusNT".user_approve; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_statusNT".user_approve IS 'รหัสพนักงานที่อนุมัติ';


--
-- TOC entry 4600 (class 0 OID 0)
-- Dependencies: 372
-- Name: COLUMN "nw_statusNT".date_approve; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_statusNT".date_approve IS 'วันที่และเวลาที่อนุมัติ';


--
-- TOC entry 373 (class 1259 OID 65470)
-- Dependencies: 15
-- Name: nw_template_autoid_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE nw_template_autoid_seq
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nw_template_autoid_seq OWNER TO dev;

--
-- TOC entry 4601 (class 0 OID 0)
-- Dependencies: 373
-- Name: SEQUENCE nw_template_autoid_seq; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON SEQUENCE nw_template_autoid_seq IS 'ใช้ run id ของจัดการ template ';


--
-- TOC entry 374 (class 1259 OID 65472)
-- Dependencies: 3329 15
-- Name: nw_template; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE nw_template (
    "tempID" integer DEFAULT nextval('nw_template_autoid_seq'::regclass) NOT NULL,
    "tempName" character varying(100),
    "tempStatus" boolean,
    "createDate" timestamp without time zone,
    id_user character varying(12)
);


ALTER TABLE public.nw_template OWNER TO dev;

--
-- TOC entry 4602 (class 0 OID 0)
-- Dependencies: 374
-- Name: TABLE nw_template; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE nw_template IS 'ตารางเก็บข้อมูลหลักของ Template';


--
-- TOC entry 4603 (class 0 OID 0)
-- Dependencies: 374
-- Name: COLUMN nw_template."tempID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_template."tempID" IS 'รหัส Template';


--
-- TOC entry 4604 (class 0 OID 0)
-- Dependencies: 374
-- Name: COLUMN nw_template."tempName"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_template."tempName" IS 'ชื่อ Template';


--
-- TOC entry 4605 (class 0 OID 0)
-- Dependencies: 374
-- Name: COLUMN nw_template."tempStatus"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_template."tempStatus" IS 'สถานะการเปิดใช้ Template
t=เปิดใช้
f=ไม่เปิดใช้';


--
-- TOC entry 4606 (class 0 OID 0)
-- Dependencies: 374
-- Name: COLUMN nw_template."createDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_template."createDate" IS 'วันที่สร้าง Template';


--
-- TOC entry 4607 (class 0 OID 0)
-- Dependencies: 374
-- Name: COLUMN nw_template.id_user; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN nw_template.id_user IS 'พนักงานที่สร้าง Template';


--
-- TOC entry 375 (class 1259 OID 65476)
-- Dependencies: 15
-- Name: nw_templateDetail; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "nw_templateDetail" (
    "tempID" integer NOT NULL,
    id_menu character varying(5) NOT NULL
);


ALTER TABLE public."nw_templateDetail" OWNER TO dev;

--
-- TOC entry 4608 (class 0 OID 0)
-- Dependencies: 375
-- Name: TABLE "nw_templateDetail"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "nw_templateDetail" IS 'ตารางเก็บรายละเอียดของ Template ว่าเก็บเมนูอะไรบ้าง';


--
-- TOC entry 4609 (class 0 OID 0)
-- Dependencies: 375
-- Name: COLUMN "nw_templateDetail"."tempID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_templateDetail"."tempID" IS 'รหัส Template';


--
-- TOC entry 4610 (class 0 OID 0)
-- Dependencies: 375
-- Name: COLUMN "nw_templateDetail".id_menu; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "nw_templateDetail".id_menu IS 'รหัสรายการ (เมนู)';


--
-- TOC entry 376 (class 1259 OID 65479)
-- Dependencies: 15
-- Name: tacReceiveTemp; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "tacReceiveTemp" (
    "tacID" character varying(20) NOT NULL,
    "tacXlsRecID" character varying(20) NOT NULL,
    "tacMoney" double precision NOT NULL,
    "tacMonth" date NOT NULL,
    "tacOldRecID" character varying(20),
    "tacTempDate" date,
    "makerID" character varying(12),
    "makerStamp" timestamp without time zone
);


ALTER TABLE public."tacReceiveTemp" OWNER TO dev;

--
-- TOC entry 4611 (class 0 OID 0)
-- Dependencies: 376
-- Name: COLUMN "tacReceiveTemp"."tacID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "tacReceiveTemp"."tacID" IS 'เลขที่สัญญา TAC';


--
-- TOC entry 4612 (class 0 OID 0)
-- Dependencies: 376
-- Name: COLUMN "tacReceiveTemp"."tacXlsRecID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "tacReceiveTemp"."tacXlsRecID" IS 'เลขที่ใบเสร็จ ไทยเอซลิสซิ่ง (สีเขียว) / ใบเสร็จชั่วคราว';


--
-- TOC entry 4613 (class 0 OID 0)
-- Dependencies: 376
-- Name: COLUMN "tacReceiveTemp"."tacMoney"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "tacReceiveTemp"."tacMoney" IS 'จำนวนเงินที่จ่ายในเดือนนั้นๆ';


--
-- TOC entry 4614 (class 0 OID 0)
-- Dependencies: 376
-- Name: COLUMN "tacReceiveTemp"."tacMonth"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "tacReceiveTemp"."tacMonth" IS 'เดือนที่เงินก้อนนี้ในใบเสร็จ Xlease/ชั่วคราวนี้ จ่าย';


--
-- TOC entry 4615 (class 0 OID 0)
-- Dependencies: 376
-- Name: COLUMN "tacReceiveTemp"."tacOldRecID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "tacReceiveTemp"."tacOldRecID" IS 'เลขที่ใบเสร็จในระบบ TAC (สัญญาบางกรณี)';


--
-- TOC entry 4616 (class 0 OID 0)
-- Dependencies: 376
-- Name: COLUMN "tacReceiveTemp"."tacTempDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "tacReceiveTemp"."tacTempDate" IS 'วันที่มาชำระเงินของใบเสร็จชั่วคราว';


--
-- TOC entry 4617 (class 0 OID 0)
-- Dependencies: 376
-- Name: COLUMN "tacReceiveTemp"."makerID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "tacReceiveTemp"."makerID" IS 'user ที่เข้าใช้งาน';


--
-- TOC entry 4618 (class 0 OID 0)
-- Dependencies: 376
-- Name: COLUMN "tacReceiveTemp"."makerStamp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "tacReceiveTemp"."makerStamp" IS 'วันที่และเวลาที่เข้าใช้งาน';


--
-- TOC entry 377 (class 1259 OID 65482)
-- Dependencies: 15
-- Name: tac_old_nt; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE tac_old_nt (
    tac_nt_running character varying(10) NOT NULL,
    tac_cusid character varying(12),
    tac_nt_date date,
    tac_nt_start date,
    tac_nt_end date,
    tac_nt_amount double precision,
    tac_maker_id character varying(12),
    tac_maker_stamp timestamp without time zone
);


ALTER TABLE public.tac_old_nt OWNER TO dev;

--
-- TOC entry 4619 (class 0 OID 0)
-- Dependencies: 377
-- Name: TABLE tac_old_nt; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE tac_old_nt IS 'ตารางเก็บข้อมูลการออก NT';


--
-- TOC entry 4620 (class 0 OID 0)
-- Dependencies: 377
-- Name: COLUMN tac_old_nt.tac_nt_running; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN tac_old_nt.tac_nt_running IS 'เลขที่ NT';


--
-- TOC entry 4621 (class 0 OID 0)
-- Dependencies: 377
-- Name: COLUMN tac_old_nt.tac_cusid; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN tac_old_nt.tac_cusid IS 'รหัส CusID';


--
-- TOC entry 389 (class 1259 OID 66408)
-- Dependencies: 15
-- Name: thcap_company; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_company (
    "compID" character varying(30) NOT NULL,
    "compThaiName" character varying(60) NOT NULL,
    "compEngName" character varying(60) NOT NULL,
    "compType" character varying(30),
    "compDesc" character varying,
    "compSort" integer NOT NULL
);


ALTER TABLE public.thcap_company OWNER TO dev;

--
-- TOC entry 4622 (class 0 OID 0)
-- Dependencies: 389
-- Name: TABLE thcap_company; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE thcap_company IS 'บริษัทภายในที่ใช้ในโปรแกรม';


--
-- TOC entry 4623 (class 0 OID 0)
-- Dependencies: 389
-- Name: COLUMN thcap_company."compID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company."compID" IS 'ID บริษัท';


--
-- TOC entry 4624 (class 0 OID 0)
-- Dependencies: 389
-- Name: COLUMN thcap_company."compThaiName"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company."compThaiName" IS 'ชื่อเต็มบริษัท ภาษาไทย';


--
-- TOC entry 4625 (class 0 OID 0)
-- Dependencies: 389
-- Name: COLUMN thcap_company."compEngName"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company."compEngName" IS 'ชื่อเต็มบริษัท ภาษาอังกฤษ';


--
-- TOC entry 4626 (class 0 OID 0)
-- Dependencies: 389
-- Name: COLUMN thcap_company."compSort"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company."compSort" IS 'เรียงลำดับบริษัท';


--
-- TOC entry 390 (class 1259 OID 66417)
-- Dependencies: 15
-- Name: thcap_company_branch; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_company_branch (
    "cBranchID" character varying(30) NOT NULL,
    "compID" character varying(30) NOT NULL,
    "cBranchName" character varying(60) NOT NULL,
    "cBranchAddr" character varying,
    "cBranchOpenDate" date,
    "bSort" integer NOT NULL
);


ALTER TABLE public.thcap_company_branch OWNER TO dev;

--
-- TOC entry 4627 (class 0 OID 0)
-- Dependencies: 390
-- Name: TABLE thcap_company_branch; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE thcap_company_branch IS 'สาขาบริษัท';


--
-- TOC entry 4628 (class 0 OID 0)
-- Dependencies: 390
-- Name: COLUMN thcap_company_branch."cBranchID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company_branch."cBranchID" IS 'รหัสสาขาบริษัท';


--
-- TOC entry 4629 (class 0 OID 0)
-- Dependencies: 390
-- Name: COLUMN thcap_company_branch."compID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company_branch."compID" IS 'รหัสบริษัทที่เป็นเข้าของสาขานั้น';


--
-- TOC entry 4630 (class 0 OID 0)
-- Dependencies: 390
-- Name: COLUMN thcap_company_branch."cBranchName"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company_branch."cBranchName" IS 'ชื่อสาขา';


--
-- TOC entry 4631 (class 0 OID 0)
-- Dependencies: 390
-- Name: COLUMN thcap_company_branch."cBranchAddr"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company_branch."cBranchAddr" IS 'ที่อยู่สาขา';


--
-- TOC entry 4632 (class 0 OID 0)
-- Dependencies: 390
-- Name: COLUMN thcap_company_branch."cBranchOpenDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company_branch."cBranchOpenDate" IS 'วันทีเปิดบริการของสาขา';


--
-- TOC entry 4633 (class 0 OID 0)
-- Dependencies: 390
-- Name: COLUMN thcap_company_branch."bSort"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_company_branch."bSort" IS 'เรียงลำดับ';


--
-- TOC entry 398 (class 1259 OID 66935)
-- Dependencies: 15
-- Name: thcap_cus_temp; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_cus_temp (
    "CusID" character varying(30) NOT NULL,
    "ConID" character varying(30),
    "CusPreName" character varying(30),
    "CusFirstName" character varying(60),
    "CusLastName" character varying(60)
);


ALTER TABLE public.thcap_cus_temp OWNER TO dev;

--
-- TOC entry 440 (class 1259 OID 69102)
-- Dependencies: 15
-- Name: thcap_mg_contract; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_mg_contract (
    "contractID" character varying(35) NOT NULL,
    "conGroupCusID" character varying(20),
    "conGroupProID" character varying(20),
    "branchID" character varying(30),
    "compID" character varying(30),
    "conLoanType" character varying(25),
    "conLoanPackage" character varying(25),
    "conLoanAmt" numeric(15,2),
    "conLoanNetAmt" numeric(15,2),
    "conLoanFee" numeric(15,2),
    "conLoanIniRate" numeric(6,2),
    futureuse01 smallint,
    "conLoanMaxRate" numeric(6,2),
    "conTerm" smallint,
    "conMinPay" numeric(15,2),
    "conPenaltyRate" numeric(15,2),
    "conDate" date,
    "conStartDate" date,
    "conEndDate" date,
    "conFirstDue" date,
    "conRepeatDueDay" character varying(2),
    "conFreeDate" date,
    "conClosedDate" date,
    "conClosedFee" numeric(6,2),
    "conStatus" smallint,
    "conFlow" smallint,
    rev integer
);


ALTER TABLE public.thcap_mg_contract OWNER TO dev;

--
-- TOC entry 4634 (class 0 OID 0)
-- Dependencies: 440
-- Name: TABLE thcap_mg_contract; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE thcap_mg_contract IS 'ข้อมูลสัญญาจำนอง';


--
-- TOC entry 4635 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."contractID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."contractID" IS 'เลขที่สัญญาจำนอง';


--
-- TOC entry 4636 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conGroupCusID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conGroupCusID" IS 'รหัสชุดลูกค้า';


--
-- TOC entry 4637 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conGroupProID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conGroupProID" IS 'รหัสชุดทรัพย์สิน';


--
-- TOC entry 4638 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conLoanType"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conLoanType" IS 'ประเภทการกู้ เช่น 
MG - เงินกู้ส่วนบุคคลมีหลักประกัน (จำนอง)
LI - เงินกู้ธุรกิจมีหลักประกัน (จำนอง)';


--
-- TOC entry 4639 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conLoanPackage"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conLoanPackage" IS 'Package/Promotion ต่างๆ เช่น
GEN-000 - Package ทั่วไป';


--
-- TOC entry 4640 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conLoanAmt"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conLoanAmt" IS 'จำนวนเงินกู้';


--
-- TOC entry 4641 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conLoanNetAmt"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conLoanNetAmt" IS 'จำนวนเงินลูกค้ารับสุทธิ';


--
-- TOC entry 4642 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conLoanFee"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conLoanFee" IS 'ค่าใช้จ่ายที่เรียกเก็บ';


--
-- TOC entry 4643 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conLoanIniRate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conLoanIniRate" IS 'อัตราดอกเบี้ยที่ตกลงตอนแรก';


--
-- TOC entry 4644 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conLoanMaxRate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conLoanMaxRate" IS 'อัตราดอกเบี้ยสูงสุด';


--
-- TOC entry 4645 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conTerm"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conTerm" IS 'ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน)';


--
-- TOC entry 4646 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conMinPay"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conMinPay" IS 'จำนวนเงินผ่อนขั้นต่ำต่อ Due';


--
-- TOC entry 4647 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conPenaltyRate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conPenaltyRate" IS 'ค่าติดตามทวงถามปัจจุบัน';


--
-- TOC entry 4648 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conDate" IS 'วันที่ทำสัญญา';


--
-- TOC entry 4649 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conStartDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conStartDate" IS 'วันที่รับเงินที่ขอกู้';


--
-- TOC entry 4650 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conEndDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conEndDate" IS 'วันที่สิ้นสุดการกู้ที่ระบุไว้ในสัญญา';


--
-- TOC entry 4651 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conFirstDue"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conFirstDue" IS 'Due แรก';


--
-- TOC entry 4652 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conRepeatDueDay"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conRepeatDueDay" IS 'Due วันที่ชำระของทุกๆเดือน เช่น 01 หรือ 28';


--
-- TOC entry 4653 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conFreeDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conFreeDate" IS 'วันที่พ้นกำหนดห้ามปิดบัญชีก่อนกำหนด (Default = กึ่งหนึ่งของระยะเวลาทั้งสัญญา)';


--
-- TOC entry 4654 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conClosedDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conClosedDate" IS 'วันที่ปิดบัญชีจริง';


--
-- TOC entry 4655 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conClosedFee"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conClosedFee" IS '% ค่าปรับปิดบัญชีก่อนกำหนด คิดจากยอดกู้';


--
-- TOC entry 4656 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conStatus"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conStatus" IS 'สถานะสัญญา / NCB';


--
-- TOC entry 4657 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract."conFlow"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract."conFlow" IS 'สถานะสัญญา / internal';


--
-- TOC entry 4658 (class 0 OID 0)
-- Dependencies: 440
-- Name: COLUMN thcap_mg_contract.rev; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract.rev IS 'เปลี่ยนแปลงสัญญาครั้งที่
(1 - เริ่มต้นสัญญา)';


--
-- TOC entry 439 (class 1259 OID 69097)
-- Dependencies: 15
-- Name: thcap_mg_contract_current; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_mg_contract_current (
    "contractID" character varying(35) NOT NULL,
    rev integer NOT NULL,
    "effectiveDate" date NOT NULL,
    "doerID" character varying(15),
    "doerStamp" timestamp without time zone,
    "appvID" character varying(15),
    "appvStamp" timestamp without time zone,
    "auditorXID" character varying(15),
    "auditorStamp" timestamp without time zone,
    "auditorYID" character varying(15),
    "auditorYStamp" timestamp without time zone,
    "conIntCurRate" numeric(6,2) NOT NULL,
    "conCurPenalty" numeric(15,2) NOT NULL,
    futureuse01 character varying(30),
    "conCurVAT" numeric(6,2) NOT NULL,
    "conCurSBT" numeric(6,2) NOT NULL,
    "conCurLT" numeric(6,2) NOT NULL,
    "conIntMethod" smallint NOT NULL,
    "conNumExceptDays" smallint NOT NULL,
    "conNumNTDays" smallint NOT NULL,
    "conNumSueDays" smallint NOT NULL
);


ALTER TABLE public.thcap_mg_contract_current OWNER TO dev;

--
-- TOC entry 4659 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current.rev; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current.rev IS 'แก้ไขครั้งที่
(1-ครั้งแรกที่ทำสัญญา)';


--
-- TOC entry 4660 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current."effectiveDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current."effectiveDate" IS 'วันที่การเปลี่ยนแปลงนี้มีผล';


--
-- TOC entry 4661 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current."conIntCurRate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current."conIntCurRate" IS 'เปลี่ยนแปลงอัตราดอกเบี้ยปัจจุบัน';


--
-- TOC entry 4662 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current."conCurPenalty"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current."conCurPenalty" IS 'ค่าติดตามทวงถามปัจจุบัน';


--
-- TOC entry 4663 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current.futureuse01; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current.futureuse01 IS '-';


--
-- TOC entry 4664 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current."conCurVAT"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current."conCurVAT" IS '% อัตราภาษีมูลค่าเพิ่ม (Value Added Tax)';


--
-- TOC entry 4665 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current."conCurSBT"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current."conCurSBT" IS '% อัตราภาษีธุรกิจเฉพาะ (Specific Business Tax)';


--
-- TOC entry 4666 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current."conCurLT"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current."conCurLT" IS '% อัตราภาษีท้องถิ่น (Local Tax)';


--
-- TOC entry 4667 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current."conNumExceptDays"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current."conNumExceptDays" IS 'จำนวนวันที่ผ่อนผันเรื่องค่าติดตามทวงถาม และการปรับอัตราดอกเบี้ย นับจาก Due';


--
-- TOC entry 4668 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current."conNumNTDays"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current."conNumNTDays" IS 'จำนวนวันที่ผ่อนผันการออกหนังสือเตือนหนี้ NT นับจาก Due';


--
-- TOC entry 4669 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN thcap_mg_contract_current."conNumSueDays"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_current."conNumSueDays" IS 'จำนวนวันที่ผ่อนผัน ก่อนการฟ้องร้อง นับจาก Due';


--
-- TOC entry 404 (class 1259 OID 67183)
-- Dependencies: 15
-- Name: thcap_mg_contract_details; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_mg_contract_details (
    "contractID" character varying(35) NOT NULL,
    "conLoanPurpose" character varying(100),
    "doerID" character varying(25),
    "doerStamp" timestamp without time zone,
    "appvID" character varying(15),
    "appvStamp" timestamp without time zone,
    "auditorXID" character varying(15),
    "auditorXStamp" timestamp without time zone,
    "auditorYID" character varying(15),
    "auditorYStamp" timestamp without time zone
);


ALTER TABLE public.thcap_mg_contract_details OWNER TO dev;

--
-- TOC entry 4670 (class 0 OID 0)
-- Dependencies: 404
-- Name: TABLE thcap_mg_contract_details; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE thcap_mg_contract_details IS 'รายละเอียดสัญญาจำนอง';


--
-- TOC entry 4671 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."contractID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."contractID" IS 'เลขที่สัญญาจำนอง';


--
-- TOC entry 4672 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."conLoanPurpose"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."conLoanPurpose" IS 'วัตถุประสงค์ในการกู้เงิน';


--
-- TOC entry 4673 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."doerID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."doerID" IS 'รหัส user ผู้คีย์สัญญา';


--
-- TOC entry 4674 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."doerStamp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."doerStamp" IS 'วันเวลาที่คีย์สัญญา หรือแก้ไขสัญญาล่าสุด';


--
-- TOC entry 4675 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."appvID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."appvID" IS 'รหัส user ผู้อนุมัติสัญญา';


--
-- TOC entry 4676 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."appvStamp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."appvStamp" IS 'วันเวลาที่ทำการอนุมัติสัญญา หรืออนุมัติเปลี่ยนแปลงเงื่อนไขสัญญา';


--
-- TOC entry 4677 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."auditorXID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."auditorXID" IS 'รหัส user ผู้ตรวจสอบ X';


--
-- TOC entry 4678 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."auditorXStamp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."auditorXStamp" IS 'วันเวลาที่ทำการตรวจสอบ';


--
-- TOC entry 4679 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."auditorYID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."auditorYID" IS 'รหัส user ผู้ตรวจสอบ Y';


--
-- TOC entry 4680 (class 0 OID 0)
-- Dependencies: 404
-- Name: COLUMN thcap_mg_contract_details."auditorYStamp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_contract_details."auditorYStamp" IS 'วันเวลาที่ทำการตรวจสอบ';


--
-- TOC entry 378 (class 1259 OID 65491)
-- Dependencies: 15
-- Name: thcap_mg_setting; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_mg_setting (
    "mgSettingID" integer NOT NULL,
    "doerID" character varying(15),
    "doerStamp" timestamp without time zone,
    "appvID" character varying(15),
    "appvStamp" timestamp without time zone,
    "mgsActiveDate" date,
    "lawMaxInterest" numeric(6,2),
    "lawMaxMonthTerm" smallint,
    "lawVATRate" numeric(6,2),
    "lawSBTRate" numeric(6,2),
    "lawLTRate" numeric(6,2),
    "comPenaltyC" numeric(15,2),
    "comMaxInterest" numeric(6,2),
    "comMaxMonthTerm" smallint,
    "comPenaltyD" numeric(15,2),
    "comLawyerFee" numeric(15,2),
    "comCloseAccFee" numeric(6,2),
    "comPenaltyF" numeric(15,2)
);


ALTER TABLE public.thcap_mg_setting OWNER TO dev;

--
-- TOC entry 4681 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."mgSettingID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."mgSettingID" IS 'running ค่า mortgage setting';


--
-- TOC entry 4682 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."doerID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."doerID" IS 'ผู้ทำรายการ';


--
-- TOC entry 4683 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."doerStamp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."doerStamp" IS 'วันเวลาที่ผู้ทำรายการทำรายการ';


--
-- TOC entry 4684 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."appvID"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."appvID" IS 'ผู้อนุมัติรายการ';


--
-- TOC entry 4685 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."appvStamp"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."appvStamp" IS 'วันเวลาที่ผู้อนุมัติรายการ อนุมัติรายการ';


--
-- TOC entry 4686 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."mgsActiveDate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."mgsActiveDate" IS 'วันที่มีค่าต่างๆจะมีผล';


--
-- TOC entry 4687 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."lawMaxInterest"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."lawMaxInterest" IS '% อัตราดอกเบี้ยสูงสุดที่กฎหมายกำหนด';


--
-- TOC entry 4688 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."lawMaxMonthTerm"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."lawMaxMonthTerm" IS 'จำนวนเดือนที่กู้ได้สูงสุดตามที่กฎหมายกำหนด';


--
-- TOC entry 4689 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."lawVATRate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."lawVATRate" IS '% อัตราภาษีมูลค่าเพิ่มที่กฎหมายกำหนด';


--
-- TOC entry 4690 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."lawSBTRate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."lawSBTRate" IS '% อัตราภาษีธุรกิจเฉพาะที่กฎหมายกำหนด';


--
-- TOC entry 4691 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."lawLTRate"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."lawLTRate" IS '% อัตราภาษีท้องถิ่นที่กฎหมายกำหนด';


--
-- TOC entry 4692 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."comPenaltyC"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."comPenaltyC" IS 'ค่าติดตามทวงถามประจำเดือน';


--
-- TOC entry 4693 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."comMaxInterest"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."comMaxInterest" IS '% อัตราดอกเบี้ยสูงสุดที่บริษัทกำหนด';


--
-- TOC entry 4694 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."comMaxMonthTerm"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."comMaxMonthTerm" IS 'ระยะเวลาการผ่อนสูงสุดที่บริษัทกำหนด';


--
-- TOC entry 4695 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."comPenaltyD"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."comPenaltyD" IS 'ค่าติดตามกรณีค้างชำระ';


--
-- TOC entry 4696 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."comLawyerFee"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."comLawyerFee" IS 'ค่าเตือนโดยทนาย';


--
-- TOC entry 4697 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."comCloseAccFee"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."comCloseAccFee" IS '% ค่าปรับปิดบัญชีก่อนกำหนด (คิดจากยอดกู้เริ่มต้น)';


--
-- TOC entry 4698 (class 0 OID 0)
-- Dependencies: 378
-- Name: COLUMN thcap_mg_setting."comPenaltyF"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN thcap_mg_setting."comPenaltyF" IS 'ค่าติดตามทวงถามกรณีมีการฟ้องร้อง';


--
-- TOC entry 379 (class 1259 OID 65494)
-- Dependencies: 15 378
-- Name: thcap_mg_setting_mgSettingID_seq; Type: SEQUENCE; Schema: public; Owner: dev
--

CREATE SEQUENCE "thcap_mg_setting_mgSettingID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."thcap_mg_setting_mgSettingID_seq" OWNER TO dev;

--
-- TOC entry 4699 (class 0 OID 0)
-- Dependencies: 379
-- Name: thcap_mg_setting_mgSettingID_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dev
--

ALTER SEQUENCE "thcap_mg_setting_mgSettingID_seq" OWNED BY thcap_mg_setting."mgSettingID";


--
-- TOC entry 437 (class 1259 OID 69070)
-- Dependencies: 3440 15
-- Name: thcap_productType; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE "thcap_productType" (
    "proID" character varying(30) NOT NULL,
    "proCompany" character varying(20) NOT NULL,
    "proName" character varying(60) NOT NULL,
    "proDesc" character varying,
    "proTable" character varying(60) NOT NULL,
    "proAbleWHT" smallint NOT NULL,
    "proSort" smallint DEFAULT 900 NOT NULL
);


ALTER TABLE public."thcap_productType" OWNER TO dev;

--
-- TOC entry 4700 (class 0 OID 0)
-- Dependencies: 437
-- Name: TABLE "thcap_productType"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE "thcap_productType" IS 'ประเภทการบริการของบริษัทต่างๆ';


--
-- TOC entry 4701 (class 0 OID 0)
-- Dependencies: 437
-- Name: COLUMN "thcap_productType"."proAbleWHT"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "thcap_productType"."proAbleWHT" IS 'มี WHT';


--
-- TOC entry 4702 (class 0 OID 0)
-- Dependencies: 437
-- Name: COLUMN "thcap_productType"."proSort"; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON COLUMN "thcap_productType"."proSort" IS 'ลำดับการเรียงเวลาแสดงผล';


--
-- TOC entry 393 (class 1259 OID 66660)
-- Dependencies: 15
-- Name: thcap_running_number; Type: TABLE; Schema: public; Owner: dev; Tablespace: 
--

CREATE TABLE thcap_running_number (
    "compID" character varying(20) NOT NULL,
    "fieldName" character varying(30) NOT NULL,
    "runningNum" bigint
);


ALTER TABLE public.thcap_running_number OWNER TO dev;

--
-- TOC entry 4703 (class 0 OID 0)
-- Dependencies: 393
-- Name: TABLE thcap_running_number; Type: COMMENT; Schema: public; Owner: dev
--

COMMENT ON TABLE thcap_running_number IS 'เก็บเลข running ของ ID key ต่างๆ';


SET search_path = refinance, pg_catalog;

--
-- TOC entry 380 (class 1259 OID 65496)
-- Dependencies: 13
-- Name: invite_auto_id_seq; Type: SEQUENCE; Schema: refinance; Owner: dev
--

CREATE SEQUENCE invite_auto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE refinance.invite_auto_id_seq OWNER TO dev;

--
-- TOC entry 381 (class 1259 OID 65498)
-- Dependencies: 3331 13
-- Name: invite; Type: TABLE; Schema: refinance; Owner: dev; Tablespace: 
--

CREATE TABLE invite (
    "inviteID" integer DEFAULT nextval('invite_auto_id_seq'::regclass) NOT NULL,
    "IDNO" character varying(25) NOT NULL,
    "CusID" character varying(12) NOT NULL,
    asset_id character varying(12) NOT NULL,
    "CusTel" character varying(20) NOT NULL,
    "KeyDate" timestamp without time zone NOT NULL,
    "inviteDate" timestamp without time zone NOT NULL,
    id_user character varying(12) NOT NULL,
    "ActiveMatch" boolean NOT NULL,
    invite_detail text
);


ALTER TABLE refinance.invite OWNER TO dev;

--
-- TOC entry 4704 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN invite."IDNO"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN invite."IDNO" IS 'เลขที่สัญญาที่โทรชวน';


--
-- TOC entry 4705 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN invite."CusID"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN invite."CusID" IS 'รหัสลูกค้าที่โทรชวน';


--
-- TOC entry 4706 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN invite.asset_id; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN invite.asset_id IS 'รหัสสินค้าที่โทรชวน';


--
-- TOC entry 4707 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN invite."CusTel"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN invite."CusTel" IS 'เบอร์โทรลูกค้า';


--
-- TOC entry 4708 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN invite."KeyDate"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN invite."KeyDate" IS 'วันที่บันทึกข้อมูล';


--
-- TOC entry 4709 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN invite."inviteDate"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN invite."inviteDate" IS 'วันเวลาที่พนักงานโทรชวนลูกค้า';


--
-- TOC entry 4710 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN invite.id_user; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN invite.id_user IS 'รหัสพนักงานที่โทรชวนลูกค้า';


--
-- TOC entry 4711 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN invite."ActiveMatch"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN invite."ActiveMatch" IS 'สถานะการจับคู่
f = ยังไม่จับคู่
t = จับคู่แล้ว';


--
-- TOC entry 382 (class 1259 OID 65505)
-- Dependencies: 13
-- Name: match_auto_id_seq; Type: SEQUENCE; Schema: refinance; Owner: dev
--

CREATE SEQUENCE match_auto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE refinance.match_auto_id_seq OWNER TO dev;

--
-- TOC entry 383 (class 1259 OID 65507)
-- Dependencies: 3332 13
-- Name: match_invite; Type: TABLE; Schema: refinance; Owner: dev; Tablespace: 
--

CREATE TABLE match_invite (
    "matchID" integer DEFAULT nextval('match_auto_id_seq'::regclass) NOT NULL,
    "inviteID" integer NOT NULL,
    "IDNO" character varying(25),
    "matchDate" timestamp without time zone NOT NULL
);


ALTER TABLE refinance.match_invite OWNER TO dev;

--
-- TOC entry 4712 (class 0 OID 0)
-- Dependencies: 383
-- Name: COLUMN match_invite."inviteID"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN match_invite."inviteID" IS 'ใช้อ้างอิงเลขที่สัญญาเก่าในตาราง invite';


--
-- TOC entry 4713 (class 0 OID 0)
-- Dependencies: 383
-- Name: COLUMN match_invite."IDNO"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN match_invite."IDNO" IS 'เลขที่สัญญาใหม่ที่จับคู่';


--
-- TOC entry 4714 (class 0 OID 0)
-- Dependencies: 383
-- Name: COLUMN match_invite."matchDate"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN match_invite."matchDate" IS 'วันเวลาที่จับคู่';


--
-- TOC entry 384 (class 1259 OID 65511)
-- Dependencies: 13
-- Name: setup_auto_id_seq; Type: SEQUENCE; Schema: refinance; Owner: dev
--

CREATE SEQUENCE setup_auto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE refinance.setup_auto_id_seq OWNER TO dev;

--
-- TOC entry 385 (class 1259 OID 65513)
-- Dependencies: 3333 13
-- Name: setup_term; Type: TABLE; Schema: refinance; Owner: dev; Tablespace: 
--

CREATE TABLE setup_term (
    "setupID" integer DEFAULT nextval('setup_auto_id_seq'::regclass) NOT NULL,
    height_term character varying(10) NOT NULL,
    low_term character varying(10) NOT NULL,
    limit_term character varying(10) NOT NULL,
    "setupDate" timestamp without time zone NOT NULL,
    id_user character varying(12)
);


ALTER TABLE refinance.setup_term OWNER TO dev;

--
-- TOC entry 4715 (class 0 OID 0)
-- Dependencies: 385
-- Name: COLUMN setup_term.height_term; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN setup_term.height_term IS 'ค่างวดสูงสุดที่คงเหลือจากงวดสุดท้าย';


--
-- TOC entry 4716 (class 0 OID 0)
-- Dependencies: 385
-- Name: COLUMN setup_term.low_term; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN setup_term.low_term IS 'ค่างวดต่ำสุดที่คงเหลือจากงวดสุดท้าย';


--
-- TOC entry 4717 (class 0 OID 0)
-- Dependencies: 385
-- Name: COLUMN setup_term.limit_term; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN setup_term.limit_term IS 'กำหนด limit กันแสดงลูกค้าอู่';


--
-- TOC entry 4718 (class 0 OID 0)
-- Dependencies: 385
-- Name: COLUMN setup_term."setupDate"; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN setup_term."setupDate" IS 'วันที่ตั้งค่า';


--
-- TOC entry 386 (class 1259 OID 65517)
-- Dependencies: 13
-- Name: user_invite; Type: TABLE; Schema: refinance; Owner: dev; Tablespace: 
--

CREATE TABLE user_invite (
    id_user character varying(12) NOT NULL,
    status_use boolean NOT NULL
);


ALTER TABLE refinance.user_invite OWNER TO dev;

--
-- TOC entry 4719 (class 0 OID 0)
-- Dependencies: 386
-- Name: COLUMN user_invite.id_user; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN user_invite.id_user IS 'รหัสพนักงานที่ชักชวนลูกค้า';


--
-- TOC entry 4720 (class 0 OID 0)
-- Dependencies: 386
-- Name: COLUMN user_invite.status_use; Type: COMMENT; Schema: refinance; Owner: dev
--

COMMENT ON COLUMN user_invite.status_use IS 'สถานะการชวนลูกค้า
f = ไม่อนุญาต
t = อนุญาต';


SET search_path = account, pg_catalog;

--
-- TOC entry 3127 (class 2604 OID 65520)
-- Dependencies: 173 172
-- Name: auto_id; Type: DEFAULT; Schema: account; Owner: dev
--

ALTER TABLE "AccountBookDetail" ALTER COLUMN auto_id SET DEFAULT nextval('"AccountBookDetail_auto_id_seq"'::regclass);


--
-- TOC entry 3129 (class 2604 OID 65521)
-- Dependencies: 175 174
-- Name: auto_id; Type: DEFAULT; Schema: account; Owner: dev
--

ALTER TABLE "AccountBookHead" ALTER COLUMN auto_id SET DEFAULT nextval('"AccountBookHead_auto_id_seq"'::regclass);


--
-- TOC entry 3137 (class 2604 OID 65522)
-- Dependencies: 181 180
-- Name: auto_id; Type: DEFAULT; Schema: account; Owner: dev
--

ALTER TABLE "FormulaAcc" ALTER COLUMN auto_id SET DEFAULT nextval('"FormulaAcc_auto_id_seq"'::regclass);


--
-- TOC entry 3191 (class 2604 OID 65523)
-- Dependencies: 207 206
-- Name: job_id; Type: DEFAULT; Schema: account; Owner: dev
--

ALTER TABLE job_voucher ALTER COLUMN job_id SET DEFAULT nextval('job_voucher_job_id_seq'::regclass);


--
-- TOC entry 3350 (class 2604 OID 67987)
-- Dependencies: 416 415 416
-- Name: dcNoteActionID; Type: DEFAULT; Schema: account; Owner: dev
--

ALTER TABLE thcap_dncn_action ALTER COLUMN "dcNoteActionID" SET DEFAULT nextval('"thcap_dncn_action_dcNoteActionID_seq"'::regclass);


--
-- TOC entry 3345 (class 2604 OID 67391)
-- Dependencies: 408 407 408
-- Name: invActionID; Type: DEFAULT; Schema: account; Owner: dev
--

ALTER TABLE thcap_invoice_action ALTER COLUMN "invActionID" SET DEFAULT nextval('"thcap_invoice_action_invActionID_seq"'::regclass);


--
-- TOC entry 3343 (class 2604 OID 67347)
-- Dependencies: 405 406 406
-- Name: recActionID; Type: DEFAULT; Schema: account; Owner: dev
--

ALTER TABLE thcap_receipt_action ALTER COLUMN "recActionID" SET DEFAULT nextval('"thcap_receipt_action_recActionID_seq"'::regclass);


--
-- TOC entry 3349 (class 2604 OID 67739)
-- Dependencies: 411 410 411
-- Name: recChannelID; Type: DEFAULT; Schema: account; Owner: dev
--

ALTER TABLE thcap_receipt_channel ALTER COLUMN "recChannelID" SET DEFAULT nextval('"thcap_receipt_channel_recChannelID_seq"'::regclass);


--
-- TOC entry 3446 (class 2604 OID 69299)
-- Dependencies: 444 443 444
-- Name: recDetailsID; Type: DEFAULT; Schema: account; Owner: dev
--

ALTER TABLE thcap_receipt_details ALTER COLUMN "recDetailsID" SET DEFAULT nextval('"thcap_receipt_details_recDetailsID_seq"'::regclass);


SET search_path = carregis, pg_catalog;

--
-- TOC entry 3198 (class 2604 OID 65524)
-- Dependencies: 216 215
-- Name: IDDetail; Type: DEFAULT; Schema: carregis; Owner: dev
--

ALTER TABLE "DetailCarTax" ALTER COLUMN "IDDetail" SET DEFAULT nextval('"DetailCarTax_IDDetail_seq"'::regclass);


SET search_path = finance, pg_catalog;

--
-- TOC entry 3341 (class 2604 OID 67063)
-- Dependencies: 402 401 402
-- Name: chqKeeperID; Type: DEFAULT; Schema: finance; Owner: dev
--

ALTER TABLE thcap_receive_cheque_keeper ALTER COLUMN "chqKeeperID" SET DEFAULT nextval('"thcap_receive_cheque_keeper_chqKeeperID_seq"'::regclass);


--
-- TOC entry 3336 (class 2604 OID 66704)
-- Dependencies: 396 395 396
-- Name: tranActionID; Type: DEFAULT; Schema: finance; Owner: dev
--

ALTER TABLE thcap_receive_transfer_action ALTER COLUMN "tranActionID" SET DEFAULT nextval('"thcap_receive_transfer_action_tranActionID_seq"'::regclass);


SET search_path = insure, pg_catalog;

--
-- TOC entry 3253 (class 2604 OID 65525)
-- Dependencies: 247 246
-- Name: auto_id; Type: DEFAULT; Schema: insure; Owner: dev
--

ALTER TABLE batch ALTER COLUMN auto_id SET DEFAULT nextval('batch_auto_id_seq'::regclass);


SET search_path = letter, pg_catalog;

--
-- TOC entry 3255 (class 2604 OID 65526)
-- Dependencies: 249 248
-- Name: auto_id; Type: DEFAULT; Schema: letter; Owner: dev
--

ALTER TABLE "SendDetail" ALTER COLUMN auto_id SET DEFAULT nextval('"SendDetail_auto_id_seq"'::regclass);


--
-- TOC entry 3257 (class 2604 OID 65527)
-- Dependencies: 253 250
-- Name: address_id; Type: DEFAULT; Schema: letter; Owner: dev
--

ALTER TABLE cus_address ALTER COLUMN address_id SET DEFAULT nextval('cus_address_address_id_seq'::regclass);


--
-- TOC entry 3435 (class 2604 OID 68922)
-- Dependencies: 433 434 434
-- Name: address_id; Type: DEFAULT; Schema: letter; Owner: dev
--

ALTER TABLE cus_address_backup ALTER COLUMN address_id SET DEFAULT nextval('cus_address_backup_address_id_seq'::regclass);


--
-- TOC entry 3263 (class 2604 OID 65528)
-- Dependencies: 262 261
-- Name: auto_id; Type: DEFAULT; Schema: letter; Owner: dev
--

ALTER TABLE type_letter ALTER COLUMN auto_id SET DEFAULT nextval('type_letter_auto_id_seq'::regclass);


SET search_path = pmain, pg_catalog;

--
-- TOC entry 3264 (class 2604 OID 65529)
-- Dependencies: 264 263
-- Name: INCREMENTAL; Type: DEFAULT; Schema: pmain; Owner: dev
--

ALTER TABLE fletter ALTER COLUMN "INCREMENTAL" SET DEFAULT nextval('"fletter_INCREMENTAL_seq"'::regclass);


SET search_path = public, pg_catalog;

--
-- TOC entry 3277 (class 2604 OID 65530)
-- Dependencies: 278 277
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "DetailCheque" ALTER COLUMN auto_id SET DEFAULT nextval('"DetailCheque_auto_id_seq"'::regclass);


--
-- TOC entry 3405 (class 2604 OID 68800)
-- Dependencies: 423 422 423
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "DetailCheque_Backup" ALTER COLUMN auto_id SET DEFAULT nextval('"DetailCheque_Backup_auto_id_seq"'::regclass);


--
-- TOC entry 3279 (class 2604 OID 65531)
-- Dependencies: 280 279
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "DetailTranpay" ALTER COLUMN auto_id SET DEFAULT nextval('"DetailTranpay_auto_id_seq"'::regclass);


--
-- TOC entry 3280 (class 2604 OID 65532)
-- Dependencies: 283 282
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "FCash" ALTER COLUMN auto_id SET DEFAULT nextval('"FCash_auto_id_seq"'::regclass);


--
-- TOC entry 3406 (class 2604 OID 68812)
-- Dependencies: 425 424 425
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "FCash_Backup" ALTER COLUMN auto_id SET DEFAULT nextval('"FCash_Backup_auto_id_seq"'::regclass);


--
-- TOC entry 3297 (class 2604 OID 65533)
-- Dependencies: 288 287
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "FTACCheque" ALTER COLUMN auto_id SET DEFAULT nextval('"FTACCheque_auto_id_seq"'::regclass);


--
-- TOC entry 3300 (class 2604 OID 65534)
-- Dependencies: 290 289
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "FTACTran" ALTER COLUMN auto_id SET DEFAULT nextval('"FTACTran_auto_id_seq"'::regclass);


--
-- TOC entry 3303 (class 2604 OID 65535)
-- Dependencies: 293 292
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "FollowUpCus" ALTER COLUMN auto_id SET DEFAULT nextval('"FollowUpCus_auto_id_seq"'::regclass);


--
-- TOC entry 3410 (class 2604 OID 68826)
-- Dependencies: 427 426 427
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "FollowUpCus_Backup" ALTER COLUMN auto_id SET DEFAULT nextval('"FollowUpCus_Backup_auto_id_seq"'::regclass);


--
-- TOC entry 3304 (class 2604 OID 65536)
-- Dependencies: 299 298
-- Name: auto_id; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "LogsAnyFunction" ALTER COLUMN auto_id SET DEFAULT nextval('"LogsAnyFunction_auto_id_seq"'::regclass);


--
-- TOC entry 3307 (class 2604 OID 65537)
-- Dependencies: 302 301
-- Name: autoid; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "NTDetail" ALTER COLUMN autoid SET DEFAULT nextval('"NTDetail_autoid_seq"'::regclass);


--
-- TOC entry 3313 (class 2604 OID 65538)
-- Dependencies: 314 311
-- Name: id_tranpay; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "TranPay" ALTER COLUMN id_tranpay SET DEFAULT nextval('"TranPay_id_tranpay_seq"'::regclass);


--
-- TOC entry 3314 (class 2604 OID 65539)
-- Dependencies: 313 312
-- Name: id_tranpay; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "TranPay_audit" ALTER COLUMN id_tranpay SET DEFAULT nextval('"TranPay_audit_id_tranpay_seq"'::regclass);


--
-- TOC entry 3322 (class 2604 OID 65540)
-- Dependencies: 352 351
-- Name: autoid; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE "logs_NTDetail" ALTER COLUMN autoid SET DEFAULT nextval('"logs_NTDetail_autoid_seq"'::regclass);


--
-- TOC entry 3330 (class 2604 OID 65541)
-- Dependencies: 379 378
-- Name: mgSettingID; Type: DEFAULT; Schema: public; Owner: dev
--

ALTER TABLE thcap_mg_setting ALTER COLUMN "mgSettingID" SET DEFAULT nextval('"thcap_mg_setting_mgSettingID_seq"'::regclass);


SET search_path = account, pg_catalog;

--
-- TOC entry 3450 (class 2606 OID 65566)
-- Dependencies: 170 170
-- Name: AcTable_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "AcTable"
    ADD CONSTRAINT "AcTable_pkey" PRIMARY KEY ("AcID");


--
-- TOC entry 3452 (class 2606 OID 65568)
-- Dependencies: 171 171
-- Name: AccCash_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "AccCash"
    ADD CONSTRAINT "AccCash_pkey" PRIMARY KEY ("AcID");


--
-- TOC entry 3454 (class 2606 OID 65570)
-- Dependencies: 172 172
-- Name: AccountBookDetail_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "AccountBookDetail"
    ADD CONSTRAINT "AccountBookDetail_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3456 (class 2606 OID 65572)
-- Dependencies: 174 174
-- Name: AccountBookHead_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "AccountBookHead"
    ADD CONSTRAINT "AccountBookHead_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3458 (class 2606 OID 65574)
-- Dependencies: 176 176
-- Name: BookBuy_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "BookBuy"
    ADD CONSTRAINT "BookBuy_pkey" PRIMARY KEY (bh_id);


--
-- TOC entry 3460 (class 2606 OID 65576)
-- Dependencies: 177 177
-- Name: ChequeAcc_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "ChequeAcc"
    ADD CONSTRAINT "ChequeAcc_pkey" PRIMARY KEY ("AcID");


--
-- TOC entry 3462 (class 2606 OID 65578)
-- Dependencies: 178 178 178
-- Name: ChequeOfCompany_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "ChequeOfCompany"
    ADD CONSTRAINT "ChequeOfCompany_pkey" PRIMARY KEY ("AcID", "ChqID");


--
-- TOC entry 3464 (class 2606 OID 65580)
-- Dependencies: 179 179
-- Name: CostOfCar_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "CostOfCar"
    ADD CONSTRAINT "CostOfCar_pkey" PRIMARY KEY ("IDNO");


--
-- TOC entry 3466 (class 2606 OID 65582)
-- Dependencies: 180 180
-- Name: FormulaAcc_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FormulaAcc"
    ADD CONSTRAINT "FormulaAcc_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3468 (class 2606 OID 65584)
-- Dependencies: 182 182
-- Name: FormulaID_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FormulaID"
    ADD CONSTRAINT "FormulaID_pkey" PRIMARY KEY (fm_id);


--
-- TOC entry 3470 (class 2606 OID 65586)
-- Dependencies: 184 184
-- Name: IntAccDetail_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "IntAccDetail"
    ADD CONSTRAINT "IntAccDetail_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3472 (class 2606 OID 65588)
-- Dependencies: 186 186
-- Name: IntAccHead_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "IntAccHead"
    ADD CONSTRAINT "IntAccHead_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3474 (class 2606 OID 65590)
-- Dependencies: 187 187
-- Name: PayID_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "PayID"
    ADD CONSTRAINT "PayID_pkey" PRIMARY KEY (monthid);


--
-- TOC entry 3476 (class 2606 OID 65592)
-- Dependencies: 188 188
-- Name: PayToCar_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "PayToCar"
    ADD CONSTRAINT "PayToCar_pkey" PRIMARY KEY (payid);


--
-- TOC entry 3478 (class 2606 OID 65594)
-- Dependencies: 189 189 189
-- Name: RptVatBuy_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "RptVatBuy"
    ADD CONSTRAINT "RptVatBuy_pkey" PRIMARY KEY (v_date, acb_id);


--
-- TOC entry 3480 (class 2606 OID 65596)
-- Dependencies: 190 190
-- Name: RunningNo_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "RunningNo"
    ADD CONSTRAINT "RunningNo_pkey" PRIMARY KEY ("RunningDate");


--
-- TOC entry 3508 (class 2606 OID 65598)
-- Dependencies: 206 206
-- Name: job_voucher_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY job_voucher
    ADD CONSTRAINT job_voucher_pkey PRIMARY KEY (job_id);


--
-- TOC entry 3511 (class 2606 OID 65600)
-- Dependencies: 208 208
-- Name: nw_voucher_type_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_voucher_type
    ADD CONSTRAINT nw_voucher_type_pkey PRIMARY KEY (vtid);


--
-- TOC entry 3483 (class 2606 OID 65602)
-- Dependencies: 192 192 192
-- Name: pk_debtbalance; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY debtbalance
    ADD CONSTRAINT pk_debtbalance PRIMARY KEY (acclosedate, idno);


--
-- TOC entry 3485 (class 2606 OID 65604)
-- Dependencies: 196 196 196
-- Name: pk_effsoyaddcom; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY effsoyaddcom
    ADD CONSTRAINT pk_effsoyaddcom PRIMARY KEY (acclosedate, idno);


--
-- TOC entry 3828 (class 2606 OID 66391)
-- Dependencies: 388 388
-- Name: thcap_channel_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_channel
    ADD CONSTRAINT thcap_channel_pkey PRIMARY KEY ("channelID");


--
-- TOC entry 3873 (class 2606 OID 67993)
-- Dependencies: 416 416
-- Name: thcap_dncn_action_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_dncn_action
    ADD CONSTRAINT thcap_dncn_action_pkey PRIMARY KEY ("dcNoteActionID");


--
-- TOC entry 3884 (class 2606 OID 68493)
-- Dependencies: 418 418
-- Name: thcap_dncn_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_dncn
    ADD CONSTRAINT thcap_dncn_pkey PRIMARY KEY ("dcNoteID");


--
-- TOC entry 3862 (class 2606 OID 67397)
-- Dependencies: 408 408
-- Name: thcap_invoice_action_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_invoice_action
    ADD CONSTRAINT thcap_invoice_action_pkey PRIMARY KEY ("invActionID");


--
-- TOC entry 3952 (class 2606 OID 69116)
-- Dependencies: 441 441 441 441 441
-- Name: thcap_invoice_contractID_invoiceTypePay_invoiceTypePayRef_i_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_invoice
    ADD CONSTRAINT "thcap_invoice_contractID_invoiceTypePay_invoiceTypePayRef_i_key" UNIQUE ("contractID", "invoiceTypePay", "invoiceTypePayRef", "invoiceStatus");


--
-- TOC entry 4721 (class 0 OID 0)
-- Dependencies: 3952
-- Name: CONSTRAINT "thcap_invoice_contractID_invoiceTypePay_invoiceTypePayRef_i_key" ON thcap_invoice; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_invoice_contractID_invoiceTypePay_invoiceTypePayRef_i_key" ON thcap_invoice IS 'ในสัญญาเดียวกัน จะเป็นไปไม่ได้ที่ จะจ่ายค่าใช้จ่าย TypePay เดียวกัน และ Ref เดียวกัน

อย่าางก็ดี ข้อมูลซ้ำกัน 3 อย่างใน Table นี้เป็นไปได้ เนื่องจาก อาจจะมีการยกยอดไป invoice ใหม่ หรือ ออก invoice เก่าซ้ำ เนื่องจากยกเลิกของเดิม';


--
-- TOC entry 3957 (class 2606 OID 69114)
-- Dependencies: 441 441
-- Name: thcap_invoice_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_invoice
    ADD CONSTRAINT thcap_invoice_pkey PRIMARY KEY ("invoiceID");


--
-- TOC entry 3974 (class 2606 OID 69622)
-- Dependencies: 446 446 446
-- Name: thcap_mg_interest_contractID_intEndDate_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_interest
    ADD CONSTRAINT "thcap_mg_interest_contractID_intEndDate_key" UNIQUE ("contractID", "intEndDate");


--
-- TOC entry 3977 (class 2606 OID 69556)
-- Dependencies: 446 446 446
-- Name: thcap_mg_interest_contractID_intSerial_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_interest
    ADD CONSTRAINT "thcap_mg_interest_contractID_intSerial_key" UNIQUE ("contractID", "intSerial");


--
-- TOC entry 4722 (class 0 OID 0)
-- Dependencies: 3977
-- Name: CONSTRAINT "thcap_mg_interest_contractID_intSerial_key" ON thcap_mg_interest; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_interest_contractID_intSerial_key" ON thcap_mg_interest IS 'ใน 1 สัญญา จะไม่มีทางที่ intSerial จะซ้ำกัน';


--
-- TOC entry 3979 (class 2606 OID 69552)
-- Dependencies: 446 446 446
-- Name: thcap_mg_interest_contractID_intStartDate_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_interest
    ADD CONSTRAINT "thcap_mg_interest_contractID_intStartDate_key" UNIQUE ("contractID", "intStartDate");


--
-- TOC entry 4723 (class 0 OID 0)
-- Dependencies: 3979
-- Name: CONSTRAINT "thcap_mg_interest_contractID_intStartDate_key" ON thcap_mg_interest; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_interest_contractID_intStartDate_key" ON thcap_mg_interest IS 'ในสัญญาเดียวกัน เป็นไปไม่ได้ว่าจะมี IntStartDate เช่นอาจจะถูก Gen จากหลาย user พร้อมกันให้ IntStartDate ไปถึง  IntEndDate ช่วงเดียวกัน หรือคนละช่วงก็ได้ ซึ่งผิด';


--
-- TOC entry 3971 (class 2606 OID 69355)
-- Dependencies: 445 445 445
-- Name: thcap_mg_invoice_payterm_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_invoice_payterm
    ADD CONSTRAINT thcap_mg_invoice_payterm_pkey PRIMARY KEY ("invoiceID", "ptNum");


--
-- TOC entry 3821 (class 2606 OID 66802)
-- Dependencies: 387 387 387
-- Name: thcap_mg_payTerm_contractID_ptNum_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "thcap_mg_payTerm"
    ADD CONSTRAINT "thcap_mg_payTerm_contractID_ptNum_key" UNIQUE ("contractID", "ptNum");


--
-- TOC entry 4724 (class 0 OID 0)
-- Dependencies: 3821
-- Name: CONSTRAINT "thcap_mg_payTerm_contractID_ptNum_key" ON "thcap_mg_payTerm"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_payTerm_contractID_ptNum_key" ON "thcap_mg_payTerm" IS 'ใน 1 สัญญา ไม่มีทางที่ งวดที่จะซ้ำกัน';


--
-- TOC entry 3823 (class 2606 OID 69096)
-- Dependencies: 387 387 387 387
-- Name: thcap_mg_payTerm_contractID_ptNum_ptDate_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "thcap_mg_payTerm"
    ADD CONSTRAINT "thcap_mg_payTerm_contractID_ptNum_ptDate_key" UNIQUE ("contractID", "ptNum", "ptDate");


--
-- TOC entry 4725 (class 0 OID 0)
-- Dependencies: 3823
-- Name: CONSTRAINT "thcap_mg_payTerm_contractID_ptNum_ptDate_key" ON "thcap_mg_payTerm"; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_payTerm_contractID_ptNum_ptDate_key" ON "thcap_mg_payTerm" IS 'ในงวดเดียวกัน ไม่มีทางที่วันจ่ายจะซ้ำกัน ของแต่ละสัญญา';


--
-- TOC entry 3498 (class 2606 OID 65608)
-- Dependencies: 204 204 204
-- Name: thcap_mg_receipt_interest_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_receipt_interest
    ADD CONSTRAINT thcap_mg_receipt_interest_pkey PRIMARY KEY ("receiptID", "intSerial");


--
-- TOC entry 3959 (class 2606 OID 69262)
-- Dependencies: 442 442
-- Name: thcap_mg_receipt_principle_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_receipt_principle
    ADD CONSTRAINT thcap_mg_receipt_principle_pkey PRIMARY KEY ("receiptID");


--
-- TOC entry 3513 (class 2606 OID 67207)
-- Dependencies: 209 209 209
-- Name: thcap_mg_statement_CNID_contractID_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_statement
    ADD CONSTRAINT "thcap_mg_statement_CNID_contractID_key" UNIQUE ("CNID", "contractID");


--
-- TOC entry 4726 (class 0 OID 0)
-- Dependencies: 3513
-- Name: CONSTRAINT "thcap_mg_statement_CNID_contractID_key" ON thcap_mg_statement; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_statement_CNID_contractID_key" ON thcap_mg_statement IS 'Credit Note 1 ใบ ใช้ได้กับ 1 สัญญา';


--
-- TOC entry 3516 (class 2606 OID 67209)
-- Dependencies: 209 209 209
-- Name: thcap_mg_statement_CNID_statementSerial_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_statement
    ADD CONSTRAINT "thcap_mg_statement_CNID_statementSerial_key" UNIQUE ("CNID", "statementSerial");


--
-- TOC entry 4727 (class 0 OID 0)
-- Dependencies: 3516
-- Name: CONSTRAINT "thcap_mg_statement_CNID_statementSerial_key" ON thcap_mg_statement; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_statement_CNID_statementSerial_key" ON thcap_mg_statement IS 'Credit Note 1 ใบทำ statement เปลี่ยนได้แค่ครั้งเดียว';


--
-- TOC entry 3518 (class 2606 OID 67211)
-- Dependencies: 209 209 209
-- Name: thcap_mg_statement_DNID_contractID_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_statement
    ADD CONSTRAINT "thcap_mg_statement_DNID_contractID_key" UNIQUE ("DNID", "contractID");


--
-- TOC entry 4728 (class 0 OID 0)
-- Dependencies: 3518
-- Name: CONSTRAINT "thcap_mg_statement_DNID_contractID_key" ON thcap_mg_statement; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_statement_DNID_contractID_key" ON thcap_mg_statement IS 'Debit Note 1 ใบ ต่อ 1 สัญญา';


--
-- TOC entry 3521 (class 2606 OID 67213)
-- Dependencies: 209 209 209
-- Name: thcap_mg_statement_DNID_statementSerial_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_statement
    ADD CONSTRAINT "thcap_mg_statement_DNID_statementSerial_key" UNIQUE ("DNID", "statementSerial");


--
-- TOC entry 4729 (class 0 OID 0)
-- Dependencies: 3521
-- Name: CONSTRAINT "thcap_mg_statement_DNID_statementSerial_key" ON thcap_mg_statement; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_statement_DNID_statementSerial_key" ON thcap_mg_statement IS 'Debit Note 1 ใบ ต่อ 1 statement';


--
-- TOC entry 3523 (class 2606 OID 67203)
-- Dependencies: 209 209 209
-- Name: thcap_mg_statement_contractID_receiptID_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_statement
    ADD CONSTRAINT "thcap_mg_statement_contractID_receiptID_key" UNIQUE ("contractID", "receiptID");


--
-- TOC entry 4730 (class 0 OID 0)
-- Dependencies: 3523
-- Name: CONSTRAINT "thcap_mg_statement_contractID_receiptID_key" ON thcap_mg_statement; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_statement_contractID_receiptID_key" ON thcap_mg_statement IS 'ใบเสร็จ 1 ใบใช้ได้กับ 1 สัญญาเท่านั้น';


--
-- TOC entry 3525 (class 2606 OID 65610)
-- Dependencies: 209 209 209
-- Name: thcap_mg_statement_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_statement
    ADD CONSTRAINT thcap_mg_statement_pkey PRIMARY KEY ("contractID", "statementSerial");


--
-- TOC entry 3527 (class 2606 OID 67205)
-- Dependencies: 209 209 209
-- Name: thcap_mg_statement_receiptID_statementSerial_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_statement
    ADD CONSTRAINT "thcap_mg_statement_receiptID_statementSerial_key" UNIQUE ("receiptID", "statementSerial");


--
-- TOC entry 4731 (class 0 OID 0)
-- Dependencies: 3527
-- Name: CONSTRAINT "thcap_mg_statement_receiptID_statementSerial_key" ON thcap_mg_statement; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_mg_statement_receiptID_statementSerial_key" ON thcap_mg_statement IS 'ใบเสร็จ 1 ใบทำ statement เปลี่ยนได้แค่ครั้งเดียว';


--
-- TOC entry 3856 (class 2606 OID 67353)
-- Dependencies: 406 406
-- Name: thcap_receipt_action_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receipt_action
    ADD CONSTRAINT thcap_receipt_action_pkey PRIMARY KEY ("recActionID");


--
-- TOC entry 3866 (class 2606 OID 67741)
-- Dependencies: 411 411
-- Name: thcap_receipt_channel_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receipt_channel
    ADD CONSTRAINT thcap_receipt_channel_pkey PRIMARY KEY ("recChannelID");


--
-- TOC entry 3864 (class 2606 OID 67497)
-- Dependencies: 409 409
-- Name: thcap_receipt_desc_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receipt_desc
    ADD CONSTRAINT thcap_receipt_desc_pkey PRIMARY KEY ("receiptID");


--
-- TOC entry 3962 (class 2606 OID 69301)
-- Dependencies: 444 444
-- Name: thcap_receipt_details_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receipt_details
    ADD CONSTRAINT thcap_receipt_details_pkey PRIMARY KEY ("recDetailsID");


--
-- TOC entry 3967 (class 2606 OID 69316)
-- Dependencies: 444 444 444
-- Name: thcap_receipt_details_receiptID_rToInvDetails_key; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receipt_details
    ADD CONSTRAINT "thcap_receipt_details_receiptID_rToInvDetails_key" UNIQUE ("receiptID", "rToInvoiceID");


--
-- TOC entry 4732 (class 0 OID 0)
-- Dependencies: 3967
-- Name: CONSTRAINT "thcap_receipt_details_receiptID_rToInvDetails_key" ON thcap_receipt_details; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_receipt_details_receiptID_rToInvDetails_key" ON thcap_receipt_details IS 'ใบเสร็จใบเดียวกัน จะไม่มีจ่าย invoice ซ้ำกันมากกว่า 1 รายการ';


--
-- TOC entry 3504 (class 2606 OID 65614)
-- Dependencies: 205 205
-- Name: thcap_receipt_pkey; Type: CONSTRAINT; Schema: account; Owner: devgroup; Tablespace: 
--

ALTER TABLE ONLY thcap_receipt
    ADD CONSTRAINT thcap_receipt_pkey PRIMARY KEY ("receiptID");


--
-- TOC entry 3944 (class 2606 OID 69090)
-- Dependencies: 438 438 438
-- Name: thcap_typePay_fixed_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "thcap_typePay_fixed"
    ADD CONSTRAINT "thcap_typePay_fixed_pkey" PRIMARY KEY ("tpID", "tpEffDate");


--
-- TOC entry 3985 (class 2606 OID 69716)
-- Dependencies: 453 453
-- Name: thcap_typePay_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "thcap_typePay"
    ADD CONSTRAINT "thcap_typePay_pkey" PRIMARY KEY ("tpID");


--
-- TOC entry 3530 (class 2606 OID 65616)
-- Dependencies: 210 210
-- Name: vender_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY vender
    ADD CONSTRAINT vender_pkey PRIMARY KEY ("VenderID");


--
-- TOC entry 3534 (class 2606 OID 65618)
-- Dependencies: 212 212
-- Name: voucher_details_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY voucher_details
    ADD CONSTRAINT voucher_details_pkey PRIMARY KEY (vc_id);


--
-- TOC entry 3532 (class 2606 OID 65620)
-- Dependencies: 211 211
-- Name: voucher_pkey; Type: CONSTRAINT; Schema: account; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY voucher
    ADD CONSTRAINT voucher_pkey PRIMARY KEY (vc_id);


SET search_path = carregis, pg_catalog;

--
-- TOC entry 3536 (class 2606 OID 65622)
-- Dependencies: 213 213
-- Name: CarID_pkey; Type: CONSTRAINT; Schema: carregis; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "CarID"
    ADD CONSTRAINT "CarID_pkey" PRIMARY KEY (monthid);


--
-- TOC entry 3540 (class 2606 OID 65624)
-- Dependencies: 214 214
-- Name: CarTaxDue_pkey; Type: CONSTRAINT; Schema: carregis; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "CarTaxDue"
    ADD CONSTRAINT "CarTaxDue_pkey" PRIMARY KEY ("IDCarTax");


--
-- TOC entry 3545 (class 2606 OID 65626)
-- Dependencies: 215 215
-- Name: DetailCarTax_pkey; Type: CONSTRAINT; Schema: carregis; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "DetailCarTax"
    ADD CONSTRAINT "DetailCarTax_pkey" PRIMARY KEY ("IDDetail");


--
-- TOC entry 3549 (class 2606 OID 65628)
-- Dependencies: 219 219
-- Name: IDTFPEN_pkey; Type: CONSTRAINT; Schema: carregis; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "TrafficPenalty"
    ADD CONSTRAINT "IDTFPEN_pkey" PRIMARY KEY ("IDTFPEN");


--
-- TOC entry 3547 (class 2606 OID 65630)
-- Dependencies: 218 218
-- Name: LogRegisChange_pkey; Type: CONSTRAINT; Schema: carregis; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "LogRegisChange"
    ADD CONSTRAINT "LogRegisChange_pkey" PRIMARY KEY ("LogID");


SET search_path = corporate, pg_catalog;

--
-- TOC entry 3551 (class 2606 OID 65632)
-- Dependencies: 220 220 220
-- Name: CReceiptNO_pkey; Type: CONSTRAINT; Schema: corporate; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "CReceiptNO"
    ADD CONSTRAINT "CReceiptNO_pkey" PRIMARY KEY (i_year, i_month);


--
-- TOC entry 3557 (class 2606 OID 65634)
-- Dependencies: 224 224
-- Name: CorpInvoice_pkey; Type: CONSTRAINT; Schema: corporate; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY corpinvoice
    ADD CONSTRAINT "CorpInvoice_pkey" PRIMARY KEY (inv_no);


--
-- TOC entry 3553 (class 2606 OID 65636)
-- Dependencies: 221 221
-- Name: type_corp_pkey; Type: CONSTRAINT; Schema: corporate; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY type_corp
    ADD CONSTRAINT type_corp_pkey PRIMARY KEY (contact_code);


SET search_path = finance, pg_catalog;

--
-- TOC entry 3844 (class 2606 OID 66822)
-- Dependencies: 397 397 397
-- Name: thcap_receive_cheque_bankChqNo_bankOutID_key; Type: CONSTRAINT; Schema: finance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receive_cheque
    ADD CONSTRAINT "thcap_receive_cheque_bankChqNo_bankOutID_key" UNIQUE ("bankChqNo", "bankOutID");


--
-- TOC entry 4733 (class 0 OID 0)
-- Dependencies: 3844
-- Name: CONSTRAINT "thcap_receive_cheque_bankChqNo_bankOutID_key" ON thcap_receive_cheque; Type: COMMENT; Schema: finance; Owner: dev
--

COMMENT ON CONSTRAINT "thcap_receive_cheque_bankChqNo_bankOutID_key" ON thcap_receive_cheque IS 'Bank จะไม่ออกเลขที่เช็คซ้ำกันใน bank เดิม';


--
-- TOC entry 3850 (class 2606 OID 66954)
-- Dependencies: 399 399
-- Name: thcap_receive_cheque_details_pkey; Type: CONSTRAINT; Schema: finance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receive_cheque_detials
    ADD CONSTRAINT thcap_receive_cheque_details_pkey PRIMARY KEY ("revChqID");


--
-- TOC entry 3852 (class 2606 OID 67065)
-- Dependencies: 402 402
-- Name: thcap_receive_cheque_keeper_pkey; Type: CONSTRAINT; Schema: finance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receive_cheque_keeper
    ADD CONSTRAINT thcap_receive_cheque_keeper_pkey PRIMARY KEY ("chqKeeperID");


--
-- TOC entry 3846 (class 2606 OID 66820)
-- Dependencies: 397 397
-- Name: thcap_receive_cheque_pkey; Type: CONSTRAINT; Schema: finance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receive_cheque
    ADD CONSTRAINT thcap_receive_cheque_pkey PRIMARY KEY ("revChqID");


--
-- TOC entry 3840 (class 2606 OID 66709)
-- Dependencies: 396 396
-- Name: thcap_receive_transfer_action_pkey; Type: CONSTRAINT; Schema: finance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receive_transfer_action
    ADD CONSTRAINT thcap_receive_transfer_action_pkey PRIMARY KEY ("tranActionID");


--
-- TOC entry 3838 (class 2606 OID 66683)
-- Dependencies: 394 394
-- Name: thcap_receive_transfer_pkey; Type: CONSTRAINT; Schema: finance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_receive_transfer
    ADD CONSTRAINT thcap_receive_transfer_pkey PRIMARY KEY ("revTranID");


SET search_path = gas, pg_catalog;

--
-- TOC entry 3563 (class 2606 OID 65638)
-- Dependencies: 227 227
-- Name: Company_pkey; Type: CONSTRAINT; Schema: gas; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Company"
    ADD CONSTRAINT "Company_pkey" PRIMARY KEY (coid);


--
-- TOC entry 3565 (class 2606 OID 65640)
-- Dependencies: 228 228 228
-- Name: GasID_pkey; Type: CONSTRAINT; Schema: gas; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "GasID"
    ADD CONSTRAINT "GasID_pkey" PRIMARY KEY (branch, monthid);


--
-- TOC entry 3567 (class 2606 OID 65642)
-- Dependencies: 229 229
-- Name: Model_pkey; Type: CONSTRAINT; Schema: gas; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Model"
    ADD CONSTRAINT "Model_pkey" PRIMARY KEY (modelid);


--
-- TOC entry 3569 (class 2606 OID 65644)
-- Dependencies: 230 230
-- Name: PayToGas_pkey; Type: CONSTRAINT; Schema: gas; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "PayToGas"
    ADD CONSTRAINT "PayToGas_pkey" PRIMARY KEY (payid);


--
-- TOC entry 3571 (class 2606 OID 65646)
-- Dependencies: 231 231
-- Name: PoGas_pkey; Type: CONSTRAINT; Schema: gas; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "PoGas"
    ADD CONSTRAINT "PoGas_pkey" PRIMARY KEY (poid);


SET search_path = insure, pg_catalog;

--
-- TOC entry 3573 (class 2606 OID 65648)
-- Dependencies: 233 233 233
-- Name: InsureCommision_pkey; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Commision"
    ADD CONSTRAINT "InsureCommision_pkey" PRIMARY KEY ("InsCompany", "CommCode");


--
-- TOC entry 3924 (class 2606 OID 68887)
-- Dependencies: 431 431
-- Name: InsureForce_Backup_pkey1; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "InsureForce_Backup"
    ADD CONSTRAINT "InsureForce_Backup_pkey1" PRIMARY KEY ("InsFIDNO");


--
-- TOC entry 3579 (class 2606 OID 65650)
-- Dependencies: 234 234
-- Name: InsureForce_pkey1; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "InsureForce"
    ADD CONSTRAINT "InsureForce_pkey1" PRIMARY KEY ("InsFIDNO");


--
-- TOC entry 3581 (class 2606 OID 65652)
-- Dependencies: 235 235 235
-- Name: InsureID_pkey; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "InsureID"
    ADD CONSTRAINT "InsureID_pkey" PRIMARY KEY (branch, monthid);


--
-- TOC entry 3583 (class 2606 OID 65654)
-- Dependencies: 236 236
-- Name: InsureInfo_pkey; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "InsureInfo"
    ADD CONSTRAINT "InsureInfo_pkey" PRIMARY KEY ("InsCompany");


--
-- TOC entry 3931 (class 2606 OID 68909)
-- Dependencies: 432 432
-- Name: InsureUnforce_Backup_pkey; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "InsureUnforce_Backup"
    ADD CONSTRAINT "InsureUnforce_Backup_pkey" PRIMARY KEY ("InsUFIDNO");


--
-- TOC entry 3590 (class 2606 OID 65656)
-- Dependencies: 237 237
-- Name: InsureUnforce_pkey; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "InsureUnforce"
    ADD CONSTRAINT "InsureUnforce_pkey" PRIMARY KEY ("InsUFIDNO");


--
-- TOC entry 3592 (class 2606 OID 65658)
-- Dependencies: 238 238
-- Name: PayToInsure_pkey; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "PayToInsure"
    ADD CONSTRAINT "PayToInsure_pkey" PRIMARY KEY ("PayID");


--
-- TOC entry 3594 (class 2606 OID 65660)
-- Dependencies: 239 239
-- Name: RateInsForce_pkey; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "RateInsForce"
    ADD CONSTRAINT "RateInsForce_pkey" PRIMARY KEY ("IFCode");


--
-- TOC entry 3607 (class 2606 OID 65662)
-- Dependencies: 246 246
-- Name: batch_pkey; Type: CONSTRAINT; Schema: insure; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY batch
    ADD CONSTRAINT batch_pkey PRIMARY KEY (auto_id);


SET search_path = letter, pg_catalog;

--
-- TOC entry 3609 (class 2606 OID 65664)
-- Dependencies: 248 248
-- Name: SendDetail_pkey; Type: CONSTRAINT; Schema: letter; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "SendDetail"
    ADD CONSTRAINT "SendDetail_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3933 (class 2606 OID 68928)
-- Dependencies: 434 434
-- Name: cus_address_backup_pkey; Type: CONSTRAINT; Schema: letter; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY cus_address_backup
    ADD CONSTRAINT cus_address_backup_pkey PRIMARY KEY (address_id);


--
-- TOC entry 3611 (class 2606 OID 65666)
-- Dependencies: 250 250
-- Name: cus_address_pkey; Type: CONSTRAINT; Schema: letter; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY cus_address
    ADD CONSTRAINT cus_address_pkey PRIMARY KEY (address_id);


--
-- TOC entry 3616 (class 2606 OID 65668)
-- Dependencies: 255 255
-- Name: dontsave_address_pkey; Type: CONSTRAINT; Schema: letter; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY dontsave_address
    ADD CONSTRAINT dontsave_address_pkey PRIMARY KEY ("dontID");


--
-- TOC entry 3618 (class 2606 OID 65670)
-- Dependencies: 257 257
-- Name: regis_sent_pkey; Type: CONSTRAINT; Schema: letter; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY regis_send
    ADD CONSTRAINT regis_sent_pkey PRIMARY KEY (reg_id);


--
-- TOC entry 3620 (class 2606 OID 65672)
-- Dependencies: 258 258
-- Name: send_address_pkey; Type: CONSTRAINT; Schema: letter; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY send_address
    ADD CONSTRAINT send_address_pkey PRIMARY KEY ("CusLetID");


--
-- TOC entry 3622 (class 2606 OID 65674)
-- Dependencies: 259 259 259
-- Name: send_detail_pkey; Type: CONSTRAINT; Schema: letter; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY send_detail
    ADD CONSTRAINT send_detail_pkey PRIMARY KEY (send_date, "sendID");


--
-- TOC entry 3624 (class 2606 OID 65676)
-- Dependencies: 260 260
-- Name: send_no_pkey; Type: CONSTRAINT; Schema: letter; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY send_no
    ADD CONSTRAINT send_no_pkey PRIMARY KEY (send_date);


--
-- TOC entry 3626 (class 2606 OID 65678)
-- Dependencies: 261 261
-- Name: type_letter_pkey; Type: CONSTRAINT; Schema: letter; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY type_letter
    ADD CONSTRAINT type_letter_pkey PRIMARY KEY (auto_id);


SET search_path = pmain, pg_catalog;

--
-- TOC entry 3628 (class 2606 OID 65680)
-- Dependencies: 263 263 263
-- Name: fletter_pkey; Type: CONSTRAINT; Schema: pmain; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY fletter
    ADD CONSTRAINT fletter_pkey PRIMARY KEY ("IDNO", "INCREMENTAL");


--
-- TOC entry 3630 (class 2606 OID 65683)
-- Dependencies: 265 265
-- Name: new_fp_trans_pkey; Type: CONSTRAINT; Schema: pmain; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY new_fp_trans
    ADD CONSTRAINT new_fp_trans_pkey PRIMARY KEY ("IDNO");


SET search_path = public, pg_catalog;

--
-- TOC entry 3633 (class 2606 OID 65685)
-- Dependencies: 266 266 266
-- Name: AccPayment_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "AccPayment"
    ADD CONSTRAINT "AccPayment_pkey" PRIMARY KEY ("IDNO", "DueNo");


--
-- TOC entry 3635 (class 2606 OID 65687)
-- Dependencies: 267 267
-- Name: BankCheque_index01; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "BankCheque"
    ADD CONSTRAINT "BankCheque_index01" PRIMARY KEY ("BankCode");


--
-- TOC entry 3637 (class 2606 OID 65689)
-- Dependencies: 268 268
-- Name: BankInt_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "BankInt"
    ADD CONSTRAINT "BankInt_pkey" PRIMARY KEY ("BAccount");


--
-- TOC entry 3639 (class 2606 OID 65691)
-- Dependencies: 269 269
-- Name: BankProfile_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "BankProfile"
    ADD CONSTRAINT "BankProfile_pkey" PRIMARY KEY ("bankID");


--
-- TOC entry 3641 (class 2606 OID 65693)
-- Dependencies: 270 270
-- Name: CancelReceipt_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "CancelReceipt"
    ADD CONSTRAINT "CancelReceipt_pkey" PRIMARY KEY (c_receipt);


--
-- TOC entry 3643 (class 2606 OID 65695)
-- Dependencies: 271 271 271
-- Name: ContactCashID_index01; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "ContactCashID"
    ADD CONSTRAINT "ContactCashID_index01" PRIMARY KEY (c_year, c_branch);


--
-- TOC entry 3892 (class 2606 OID 68783)
-- Dependencies: 420 420 420
-- Name: ContactCus_Backup_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "ContactCus_Backup"
    ADD CONSTRAINT "ContactCus_Backup_pkey" PRIMARY KEY ("IDNO", "CusState");


--
-- TOC entry 3614 (class 2606 OID 65697)
-- Dependencies: 251 251 251
-- Name: ContactCus_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "ContactCus"
    ADD CONSTRAINT "ContactCus_pkey" PRIMARY KEY ("IDNO", "CusState");


--
-- TOC entry 3645 (class 2606 OID 65699)
-- Dependencies: 272 272 272
-- Name: ContactID_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "ContactID"
    ADD CONSTRAINT "ContactID_pkey" PRIMARY KEY (branch, monthid);


--
-- TOC entry 3648 (class 2606 OID 65701)
-- Dependencies: 273 273 273
-- Name: CusPayment_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "CusPayment"
    ADD CONSTRAINT "CusPayment_pkey" PRIMARY KEY ("IDNO", "DueNo");


--
-- TOC entry 3894 (class 2606 OID 68794)
-- Dependencies: 421 421
-- Name: Customer_Temp_Backup_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Customer_Temp_Backup"
    ADD CONSTRAINT "Customer_Temp_Backup_pkey" PRIMARY KEY ("CustempID");


--
-- TOC entry 3650 (class 2606 OID 65711)
-- Dependencies: 275 275
-- Name: Customer_Temp_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Customer_Temp"
    ADD CONSTRAINT "Customer_Temp_pkey" PRIMARY KEY ("CustempID");


--
-- TOC entry 3652 (class 2606 OID 65713)
-- Dependencies: 276 276 276
-- Name: DTACCheque_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "DTACCheque"
    ADD CONSTRAINT "DTACCheque_pkey" PRIMARY KEY ("D_ChequeNo", "D_BankID");


--
-- TOC entry 3900 (class 2606 OID 68802)
-- Dependencies: 423 423
-- Name: DetailCheque_Backup_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "DetailCheque_Backup"
    ADD CONSTRAINT "DetailCheque_Backup_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3658 (class 2606 OID 65715)
-- Dependencies: 277 277
-- Name: DetailCheque_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "DetailCheque"
    ADD CONSTRAINT "DetailCheque_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3663 (class 2606 OID 65717)
-- Dependencies: 279 279
-- Name: DetailTranpay_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "DetailTranpay"
    ADD CONSTRAINT "DetailTranpay_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3665 (class 2606 OID 65719)
-- Dependencies: 281 281
-- Name: DocumentType_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "DocumentType"
    ADD CONSTRAINT "DocumentType_pkey" PRIMARY KEY (id);


--
-- TOC entry 3905 (class 2606 OID 68817)
-- Dependencies: 425 425
-- Name: FCash_Backup_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FCash_Backup"
    ADD CONSTRAINT "FCash_Backup_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3670 (class 2606 OID 65721)
-- Dependencies: 282 282
-- Name: FCash_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FCash"
    ADD CONSTRAINT "FCash_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3672 (class 2606 OID 65723)
-- Dependencies: 284 284 284
-- Name: FCheque_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FCheque"
    ADD CONSTRAINT "FCheque_pkey" PRIMARY KEY ("PostID", "ChequeNo");


--
-- TOC entry 3677 (class 2606 OID 65725)
-- Dependencies: 285 285
-- Name: FGas_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FGas"
    ADD CONSTRAINT "FGas_pkey" PRIMARY KEY ("GasID");


--
-- TOC entry 3561 (class 2606 OID 65727)
-- Dependencies: 225 225 225
-- Name: FOtherpay_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FOtherpay"
    ADD CONSTRAINT "FOtherpay_pkey" PRIMARY KEY ("IDNO", "O_RECEIPT");


--
-- TOC entry 3679 (class 2606 OID 65729)
-- Dependencies: 286 286
-- Name: FReceiptNO_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FReceiptNO"
    ADD CONSTRAINT "FReceiptNO_pkey" PRIMARY KEY ("Rec_date");


--
-- TOC entry 3684 (class 2606 OID 65731)
-- Dependencies: 287 287
-- Name: FTACCheque_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FTACCheque"
    ADD CONSTRAINT "FTACCheque_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3688 (class 2606 OID 65733)
-- Dependencies: 289 289
-- Name: FTACTran_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FTACTran"
    ADD CONSTRAINT "FTACTran_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3488 (class 2606 OID 65735)
-- Dependencies: 198 198 198 198
-- Name: FVat_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FVat"
    ADD CONSTRAINT "FVat_pkey" PRIMARY KEY ("IDNO", "V_DueNo", "V_Receipt");


--
-- TOC entry 3915 (class 2606 OID 68855)
-- Dependencies: 429 429
-- Name: Fa1_Backup_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fa1_Backup"
    ADD CONSTRAINT "Fa1_Backup_pkey" PRIMARY KEY ("CusID");


--
-- TOC entry 3598 (class 2606 OID 65739)
-- Dependencies: 240 240
-- Name: Fa1_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fa1"
    ADD CONSTRAINT "Fa1_pkey" PRIMARY KEY ("CusID");


--
-- TOC entry 3937 (class 2606 OID 68958)
-- Dependencies: 435 435
-- Name: Fa1_temp_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fa1_temp"
    ADD CONSTRAINT "Fa1_temp_pkey" PRIMARY KEY ("CusID");


--
-- TOC entry 3604 (class 2606 OID 65741)
-- Dependencies: 241 241
-- Name: Fc_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fc"
    ADD CONSTRAINT "Fc_pkey" PRIMARY KEY ("CarID");


--
-- TOC entry 3918 (class 2606 OID 68867)
-- Dependencies: 430 430
-- Name: Fn_Backup_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fn_Backup"
    ADD CONSTRAINT "Fn_Backup_pkey" PRIMARY KEY ("CusID");


--
-- TOC entry 3691 (class 2606 OID 65743)
-- Dependencies: 291 291
-- Name: Fn_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fn"
    ADD CONSTRAINT "Fn_pkey" PRIMARY KEY ("CusID");


--
-- TOC entry 3940 (class 2606 OID 68970)
-- Dependencies: 436 436
-- Name: Fn_temp_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fn_temp"
    ADD CONSTRAINT "Fn_temp_pkey" PRIMARY KEY ("CusID");


--
-- TOC entry 3909 (class 2606 OID 68831)
-- Dependencies: 427 427
-- Name: FollowUpCus_Backup_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FollowUpCus_Backup"
    ADD CONSTRAINT "FollowUpCus_Backup_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3695 (class 2606 OID 65745)
-- Dependencies: 292 292
-- Name: FollowUpCus_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FollowUpCus"
    ADD CONSTRAINT "FollowUpCus_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3555 (class 2606 OID 65747)
-- Dependencies: 222 222
-- Name: FpOutCus_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "FpOutCus"
    ADD CONSTRAINT "FpOutCus_pkey" PRIMARY KEY ("IDNO");


--
-- TOC entry 3889 (class 2606 OID 68774)
-- Dependencies: 419 419
-- Name: Fp_Backup_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fp_Backup"
    ADD CONSTRAINT "Fp_Backup_pkey" PRIMARY KEY ("IDNO");


--
-- TOC entry 3697 (class 2606 OID 65749)
-- Dependencies: 294 294
-- Name: Fp_Note_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fp_Note"
    ADD CONSTRAINT "Fp_Note_pkey" PRIMARY KEY ("IDNO");


--
-- TOC entry 3878 (class 2606 OID 68029)
-- Dependencies: 417 417
-- Name: Fp_TestMigrate_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fp_TestMigrate"
    ADD CONSTRAINT "Fp_TestMigrate_pkey" PRIMARY KEY ("IDNO");


--
-- TOC entry 3493 (class 2606 OID 65751)
-- Dependencies: 199 199
-- Name: Fp_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fp"
    ADD CONSTRAINT "Fp_pkey" PRIMARY KEY ("IDNO");


--
-- TOC entry 3496 (class 2606 OID 65753)
-- Dependencies: 200 200 200 200
-- Name: Fr_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Fr"
    ADD CONSTRAINT "Fr_pkey" PRIMARY KEY ("IDNO", "R_DueNo", "R_Receipt");


--
-- TOC entry 3911 (class 2606 OID 68846)
-- Dependencies: 428 428 428
-- Name: GroupCus_Active_Backup_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "GroupCus_Active_Backup"
    ADD CONSTRAINT "GroupCus_Active_Backup_pkey" PRIMARY KEY ("GroupCusID", "CusState");


--
-- TOC entry 3701 (class 2606 OID 65756)
-- Dependencies: 296 296 296
-- Name: GroupCus_Active_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "GroupCus_Active"
    ADD CONSTRAINT "GroupCus_Active_pkey" PRIMARY KEY ("GroupCusID", "CusState");


--
-- TOC entry 3703 (class 2606 OID 65758)
-- Dependencies: 297 297 297
-- Name: GroupCus_Bin_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "GroupCus_Bin"
    ADD CONSTRAINT "GroupCus_Bin_pkey" PRIMARY KEY ("GroupCusID", "CusState");


--
-- TOC entry 3699 (class 2606 OID 65760)
-- Dependencies: 295 295
-- Name: GroupCus_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "GroupCus"
    ADD CONSTRAINT "GroupCus_pkey" PRIMARY KEY ("GroupCusID");


--
-- TOC entry 3705 (class 2606 OID 65762)
-- Dependencies: 298 298
-- Name: LogsAnyFunction_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "LogsAnyFunction"
    ADD CONSTRAINT "LogsAnyFunction_pkey" PRIMARY KEY (auto_id);


--
-- TOC entry 3709 (class 2606 OID 65764)
-- Dependencies: 300 300
-- Name: MRR_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "MRR"
    ADD CONSTRAINT "MRR_pkey" PRIMARY KEY ("DateInput");


--
-- TOC entry 3712 (class 2606 OID 65766)
-- Dependencies: 301 301
-- Name: NTDetail_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "NTDetail"
    ADD CONSTRAINT "NTDetail_pkey" PRIMARY KEY (autoid);


--
-- TOC entry 3715 (class 2606 OID 65768)
-- Dependencies: 303 303
-- Name: NTHead_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "NTHead"
    ADD CONSTRAINT "NTHead_pkey" PRIMARY KEY ("NTID");


--
-- TOC entry 3719 (class 2606 OID 65770)
-- Dependencies: 305 305
-- Name: PayTypeFromAnyPlace_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "PayTypeFromAnyPlace"
    ADD CONSTRAINT "PayTypeFromAnyPlace_pkey" PRIMARY KEY (ptanyplace);


--
-- TOC entry 3717 (class 2606 OID 65772)
-- Dependencies: 304 304
-- Name: PayType_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "PayType"
    ADD CONSTRAINT "PayType_pkey" PRIMARY KEY ("PayTypeID");


--
-- TOC entry 3722 (class 2606 OID 65774)
-- Dependencies: 306 306
-- Name: PostLog_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "PostLog"
    ADD CONSTRAINT "PostLog_pkey" PRIMARY KEY ("PostID");


--
-- TOC entry 3732 (class 2606 OID 65776)
-- Dependencies: 308 308
-- Name: RadioContract_Bin_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "RadioContract_Bin"
    ADD CONSTRAINT "RadioContract_Bin_pkey" PRIMARY KEY ("COID");


--
-- TOC entry 3727 (class 2606 OID 65778)
-- Dependencies: 307 307
-- Name: RadioContract_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "RadioContract"
    ADD CONSTRAINT "RadioContract_pkey" PRIMARY KEY ("COID");


--
-- TOC entry 3736 (class 2606 OID 65780)
-- Dependencies: 310 310
-- Name: Taxiacc_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "Taxiacc"
    ADD CONSTRAINT "Taxiacc_pkey" PRIMARY KEY ("CusID");


--
-- TOC entry 3742 (class 2606 OID 65782)
-- Dependencies: 312 312 312
-- Name: TranPay_audit_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "TranPay_audit"
    ADD CONSTRAINT "TranPay_audit_pkey" PRIMARY KEY (id_tranpay, "auditNum");


--
-- TOC entry 3739 (class 2606 OID 65784)
-- Dependencies: 311 311 311 311 311 311 311
-- Name: TranPay_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "TranPay"
    ADD CONSTRAINT "TranPay_pkey" PRIMARY KEY (branch_id, tr_date, tr_time, pay_bank_branch, terminal_id, id_tranpay);


--
-- TOC entry 3744 (class 2606 OID 65786)
-- Dependencies: 315 315
-- Name: TypeOfAsset_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "TypeOfAsset"
    ADD CONSTRAINT "TypeOfAsset_pkey" PRIMARY KEY (asset_type);


--
-- TOC entry 3746 (class 2606 OID 65788)
-- Dependencies: 316 316
-- Name: TypePay_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "TypePay"
    ADD CONSTRAINT "TypePay_pkey" PRIMARY KEY ("TypeID");


--
-- TOC entry 3750 (class 2606 OID 65790)
-- Dependencies: 343 343
-- Name: bankofcompany_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY bankofcompany
    ADD CONSTRAINT bankofcompany_pkey PRIMARY KEY (accno);


--
-- TOC entry 3752 (class 2606 OID 65792)
-- Dependencies: 344 344
-- Name: branch_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY branch
    ADD CONSTRAINT branch_pkey PRIMARY KEY (brn_id);


--
-- TOC entry 3754 (class 2606 OID 65794)
-- Dependencies: 345 345
-- Name: department_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY department
    ADD CONSTRAINT department_pkey PRIMARY KEY (dep_id);


--
-- TOC entry 3748 (class 2606 OID 65796)
-- Dependencies: 340 340
-- Name: fUser_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY fuser
    ADD CONSTRAINT "fUser_pkey" PRIMARY KEY (id_user);


--
-- TOC entry 3756 (class 2606 OID 65798)
-- Dependencies: 346 346
-- Name: f_department_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY f_department
    ADD CONSTRAINT f_department_pkey PRIMARY KEY (fdep_id);


--
-- TOC entry 3758 (class 2606 OID 65800)
-- Dependencies: 347 347
-- Name: f_groupuser_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY f_groupuser
    ADD CONSTRAINT f_groupuser_pkey PRIMARY KEY (id_qroup);


--
-- TOC entry 3760 (class 2606 OID 65802)
-- Dependencies: 348 348
-- Name: f_menu_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY f_menu
    ADD CONSTRAINT f_menu_pkey PRIMARY KEY (id_menu);


--
-- TOC entry 3762 (class 2606 OID 65804)
-- Dependencies: 349 349 349
-- Name: f_usermenu_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY f_usermenu
    ADD CONSTRAINT f_usermenu_pkey PRIMARY KEY (id_menu, id_user);


--
-- TOC entry 3764 (class 2606 OID 65806)
-- Dependencies: 350 350
-- Name: fuser_detail_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY fuser_detail
    ADD CONSTRAINT fuser_detail_pkey PRIMARY KEY (id_user);


--
-- TOC entry 3767 (class 2606 OID 65808)
-- Dependencies: 351 351
-- Name: logs_NTDetail_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "logs_NTDetail"
    ADD CONSTRAINT "logs_NTDetail_pkey" PRIMARY KEY (autoid);


--
-- TOC entry 3769 (class 2606 OID 65810)
-- Dependencies: 353 353
-- Name: logs_nw_login_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY logs_nw_login
    ADD CONSTRAINT logs_nw_login_pkey PRIMARY KEY (logid);


--
-- TOC entry 3773 (class 2606 OID 65812)
-- Dependencies: 355 355
-- Name: logs_nw_regis_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY logs_nw_regis
    ADD CONSTRAINT logs_nw_regis_pkey PRIMARY KEY ("logsID");


--
-- TOC entry 3775 (class 2606 OID 65814)
-- Dependencies: 356 356
-- Name: nw_annoucefile_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_annoucefile
    ADD CONSTRAINT nw_annoucefile_pkey PRIMARY KEY ("annfileId");


--
-- TOC entry 3777 (class 2606 OID 65816)
-- Dependencies: 357 357
-- Name: nw_annoucement_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_annoucement
    ADD CONSTRAINT nw_annoucement_pkey PRIMARY KEY ("annId");


--
-- TOC entry 3779 (class 2606 OID 65818)
-- Dependencies: 358 358
-- Name: nw_annoucetype_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_annoucetype
    ADD CONSTRAINT nw_annoucetype_pkey PRIMARY KEY ("typeAnnId");


--
-- TOC entry 3782 (class 2606 OID 65820)
-- Dependencies: 360 360
-- Name: nw_annouceuser_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_annouceuser
    ADD CONSTRAINT nw_annouceuser_pkey PRIMARY KEY ("annuserId");


--
-- TOC entry 3784 (class 2606 OID 65822)
-- Dependencies: 362 362
-- Name: nw_changemenu_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_changemenu
    ADD CONSTRAINT nw_changemenu_pkey PRIMARY KEY ("changeID");


--
-- TOC entry 3786 (class 2606 OID 65824)
-- Dependencies: 363 363
-- Name: nw_createVIP_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "nw_createVIP"
    ADD CONSTRAINT "nw_createVIP_pkey" PRIMARY KEY ("IDNO");


--
-- TOC entry 3788 (class 2606 OID 65826)
-- Dependencies: 364 364
-- Name: nw_credit_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_credit
    ADD CONSTRAINT nw_credit_pkey PRIMARY KEY ("creditID");


--
-- TOC entry 3790 (class 2606 OID 65828)
-- Dependencies: 366 366
-- Name: nw_organize_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_organize
    ADD CONSTRAINT nw_organize_pkey PRIMARY KEY ("organizeID");


--
-- TOC entry 3792 (class 2606 OID 65830)
-- Dependencies: 367 367
-- Name: nw_province_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_province
    ADD CONSTRAINT nw_province_pkey PRIMARY KEY ("proID");


--
-- TOC entry 3796 (class 2606 OID 65832)
-- Dependencies: 370 370
-- Name: nw_startDateFp_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "nw_startDateFp"
    ADD CONSTRAINT "nw_startDateFp_pkey" PRIMARY KEY ("IDNO");


--
-- TOC entry 3800 (class 2606 OID 65834)
-- Dependencies: 372 372
-- Name: nw_statusNT_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "nw_statusNT"
    ADD CONSTRAINT "nw_statusNT_pkey" PRIMARY KEY ("statusID");


--
-- TOC entry 3804 (class 2606 OID 65836)
-- Dependencies: 375 375 375
-- Name: nw_templateDetail_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "nw_templateDetail"
    ADD CONSTRAINT "nw_templateDetail_pkey" PRIMARY KEY ("tempID", id_menu);


--
-- TOC entry 3802 (class 2606 OID 65838)
-- Dependencies: 374 374
-- Name: nw_template_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_template
    ADD CONSTRAINT nw_template_pkey PRIMARY KEY ("tempID");


--
-- TOC entry 3794 (class 2606 OID 65840)
-- Dependencies: 369 369
-- Name: seize_car_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY nw_seize_car
    ADD CONSTRAINT seize_car_pkey PRIMARY KEY ("seizeID");


--
-- TOC entry 3806 (class 2606 OID 65842)
-- Dependencies: 376 376 376 376
-- Name: tacReceiveTemp_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "tacReceiveTemp"
    ADD CONSTRAINT "tacReceiveTemp_pkey" PRIMARY KEY ("tacID", "tacXlsRecID", "tacMonth");


--
-- TOC entry 3808 (class 2606 OID 65844)
-- Dependencies: 377 377
-- Name: tac_old_nt_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY tac_old_nt
    ADD CONSTRAINT tac_old_nt_pkey PRIMARY KEY (tac_nt_running);


--
-- TOC entry 3832 (class 2606 OID 66424)
-- Dependencies: 390 390 390
-- Name: thcap_company_branch_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_company_branch
    ADD CONSTRAINT thcap_company_branch_pkey PRIMARY KEY ("cBranchID", "compID");


--
-- TOC entry 3830 (class 2606 OID 66415)
-- Dependencies: 389 389
-- Name: thcap_company_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_company
    ADD CONSTRAINT thcap_company_pkey PRIMARY KEY ("compID");


--
-- TOC entry 3848 (class 2606 OID 66939)
-- Dependencies: 398 398
-- Name: thcap_cus_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_cus_temp
    ADD CONSTRAINT thcap_cus_pkey PRIMARY KEY ("CusID");


--
-- TOC entry 3947 (class 2606 OID 69101)
-- Dependencies: 439 439 439
-- Name: thcap_mg_contract_current_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_contract_current
    ADD CONSTRAINT thcap_mg_contract_current_pkey PRIMARY KEY ("contractID", rev);


--
-- TOC entry 3854 (class 2606 OID 67187)
-- Dependencies: 404 404
-- Name: thcap_mg_contract_details_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_contract_details
    ADD CONSTRAINT thcap_mg_contract_details_pkey PRIMARY KEY ("contractID");


--
-- TOC entry 3949 (class 2606 OID 69106)
-- Dependencies: 440 440
-- Name: thcap_mg_contract_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_contract
    ADD CONSTRAINT thcap_mg_contract_pkey PRIMARY KEY ("contractID");


--
-- TOC entry 3811 (class 2606 OID 65850)
-- Dependencies: 378 378
-- Name: thcap_mg_setting_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_mg_setting
    ADD CONSTRAINT thcap_mg_setting_pkey PRIMARY KEY ("mgSettingID");


--
-- TOC entry 3942 (class 2606 OID 69077)
-- Dependencies: 437 437
-- Name: thcap_productType_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY "thcap_productType"
    ADD CONSTRAINT "thcap_productType_pkey" PRIMARY KEY ("proID");


--
-- TOC entry 3834 (class 2606 OID 68116)
-- Dependencies: 393 393 393
-- Name: thcap_running_number_pkey; Type: CONSTRAINT; Schema: public; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY thcap_running_number
    ADD CONSTRAINT thcap_running_number_pkey PRIMARY KEY ("compID", "fieldName");


SET search_path = refinance, pg_catalog;

--
-- TOC entry 3813 (class 2606 OID 65852)
-- Dependencies: 381 381
-- Name: invite_pkey; Type: CONSTRAINT; Schema: refinance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY invite
    ADD CONSTRAINT invite_pkey PRIMARY KEY ("inviteID");


--
-- TOC entry 3815 (class 2606 OID 65854)
-- Dependencies: 383 383
-- Name: match_invite_pkey; Type: CONSTRAINT; Schema: refinance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY match_invite
    ADD CONSTRAINT match_invite_pkey PRIMARY KEY ("matchID");


--
-- TOC entry 3817 (class 2606 OID 65856)
-- Dependencies: 385 385
-- Name: setup_term_pkey; Type: CONSTRAINT; Schema: refinance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY setup_term
    ADD CONSTRAINT setup_term_pkey PRIMARY KEY ("setupID");


--
-- TOC entry 3819 (class 2606 OID 65858)
-- Dependencies: 386 386
-- Name: user_invite_pkey; Type: CONSTRAINT; Schema: refinance; Owner: dev; Tablespace: 
--

ALTER TABLE ONLY user_invite
    ADD CONSTRAINT user_invite_pkey PRIMARY KEY (id_user);


SET search_path = account, pg_catalog;

--
-- TOC entry 3481 (class 1259 OID 65859)
-- Dependencies: 192
-- Name: debtbalance_cusid_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX debtbalance_cusid_idx ON debtbalance USING btree (cusid);


--
-- TOC entry 3960 (class 1259 OID 69705)
-- Dependencies: 444
-- Name: fki_thcap_receipt_details_rToInvoiceID_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "fki_thcap_receipt_details_rToInvoiceID_fkey" ON thcap_receipt_details USING btree ("rToInvoiceID");


--
-- TOC entry 3506 (class 1259 OID 65860)
-- Dependencies: 206
-- Name: job_voucher_end_date_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX job_voucher_end_date_idx ON job_voucher USING btree (end_date);


--
-- TOC entry 3509 (class 1259 OID 65861)
-- Dependencies: 206
-- Name: job_voucher_st_date_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX job_voucher_st_date_idx ON job_voucher USING btree (st_date);


--
-- TOC entry 3871 (class 1259 OID 67999)
-- Dependencies: 416
-- Name: thcap_dncn_action_dcNoteID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_dncn_action_dcNoteID_idx" ON thcap_dncn_action USING btree ("dcNoteID");


--
-- TOC entry 3879 (class 1259 OID 68494)
-- Dependencies: 418
-- Name: thcap_dncn_dcNoteDate_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_dncn_dcNoteDate_idx" ON thcap_dncn USING btree ("dcNoteDate");


--
-- TOC entry 3880 (class 1259 OID 68540)
-- Dependencies: 418
-- Name: thcap_dncn_invoiceID_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_dncn_invoiceID_fkey" ON thcap_dncn USING btree ("invoiceID");


--
-- TOC entry 3881 (class 1259 OID 68495)
-- Dependencies: 418
-- Name: thcap_dncn_invoiceID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_dncn_invoiceID_idx" ON thcap_dncn USING btree ("invoiceID");


--
-- TOC entry 3882 (class 1259 OID 69167)
-- Dependencies: 418
-- Name: thcap_dncn_invoiceid_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX thcap_dncn_invoiceid_fkey ON thcap_dncn USING btree ("invoiceID");


--
-- TOC entry 3858 (class 1259 OID 68518)
-- Dependencies: 408
-- Name: thcap_invoice_action_invoiceID_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_invoice_action_invoiceID_fkey" ON thcap_invoice_action USING btree ("invoiceID");


--
-- TOC entry 3859 (class 1259 OID 67403)
-- Dependencies: 408
-- Name: thcap_invoice_action_invoiceID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_invoice_action_invoiceID_idx" ON thcap_invoice_action USING btree ("invoiceID");


--
-- TOC entry 3860 (class 1259 OID 69141)
-- Dependencies: 408
-- Name: thcap_invoice_action_invoiceid_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX thcap_invoice_action_invoiceid_fkey ON thcap_invoice_action USING btree ("invoiceID");


--
-- TOC entry 3950 (class 1259 OID 69122)
-- Dependencies: 441
-- Name: thcap_invoice_contractID_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_invoice_contractID_fkey" ON thcap_invoice USING btree ("contractID");


--
-- TOC entry 3953 (class 1259 OID 69123)
-- Dependencies: 441
-- Name: thcap_invoice_invTypePay_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_invoice_invTypePay_fkey" ON thcap_invoice USING btree ("invoiceTypePay");


--
-- TOC entry 3954 (class 1259 OID 69124)
-- Dependencies: 441
-- Name: thcap_invoice_invoiceTypePay_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_invoice_invoiceTypePay_fkey" ON thcap_invoice USING btree ("invoiceTypePay");


--
-- TOC entry 3955 (class 1259 OID 69733)
-- Dependencies: 441
-- Name: thcap_invoice_invoicetypepay_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX thcap_invoice_invoicetypepay_fkey ON thcap_invoice USING btree ("invoiceTypePay");


--
-- TOC entry 3972 (class 1259 OID 69589)
-- Dependencies: 446
-- Name: thcap_mg_interest_contractID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_mg_interest_contractID_idx" ON thcap_mg_interest USING btree ("contractID");


--
-- TOC entry 3975 (class 1259 OID 69590)
-- Dependencies: 446 446
-- Name: thcap_mg_interest_contractID_intSerial_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_mg_interest_contractID_intSerial_idx" ON thcap_mg_interest USING btree ("contractID", "intSerial");


--
-- TOC entry 3980 (class 1259 OID 69553)
-- Dependencies: 446
-- Name: thcap_mg_interest_intEndDate_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_mg_interest_intEndDate_idx" ON thcap_mg_interest USING btree ("intEndDate");


--
-- TOC entry 3981 (class 1259 OID 69554)
-- Dependencies: 446
-- Name: thcap_mg_interest_intStartDate_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_mg_interest_intStartDate_idx" ON thcap_mg_interest USING btree ("intStartDate");


--
-- TOC entry 3969 (class 1259 OID 69356)
-- Dependencies: 445
-- Name: thcap_mg_invoice_payterm_invoiceID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_mg_invoice_payterm_invoiceID_idx" ON thcap_mg_invoice_payterm USING btree ("invoiceID");


--
-- TOC entry 3826 (class 1259 OID 67312)
-- Dependencies: 387
-- Name: thcap_mg_payTerm_ptDate_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_mg_payTerm_ptDate_idx" ON "thcap_mg_payTerm" USING btree ("ptDate");


--
-- TOC entry 3499 (class 1259 OID 66174)
-- Dependencies: 204
-- Name: thcap_mg_receipt_interest_receiptID_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_mg_receipt_interest_receiptID_fkey" ON thcap_mg_receipt_interest USING btree ("receiptID");


--
-- TOC entry 3514 (class 1259 OID 67310)
-- Dependencies: 209
-- Name: thcap_mg_statement_CNID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_mg_statement_CNID_idx" ON thcap_mg_statement USING btree ("CNID");


--
-- TOC entry 3519 (class 1259 OID 67311)
-- Dependencies: 209
-- Name: thcap_mg_statement_DNID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_mg_statement_DNID_idx" ON thcap_mg_statement USING btree ("DNID");


--
-- TOC entry 3500 (class 1259 OID 67323)
-- Dependencies: 205
-- Name: thcap_receipt_action_receiptID_fkey; Type: INDEX; Schema: account; Owner: devgroup; Tablespace: 
--

CREATE INDEX "thcap_receipt_action_receiptID_fkey" ON thcap_receipt USING btree ("receiptID");


--
-- TOC entry 3857 (class 1259 OID 67354)
-- Dependencies: 406
-- Name: thcap_receipt_action_receiptID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_action_receiptID_idx" ON thcap_receipt_action USING btree ("receiptID");


--
-- TOC entry 3867 (class 1259 OID 67750)
-- Dependencies: 411
-- Name: thcap_receipt_channel_recChannelRef_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_channel_recChannelRef_idx" ON thcap_receipt_channel USING btree ("recChannelRef");


--
-- TOC entry 3868 (class 1259 OID 67747)
-- Dependencies: 411
-- Name: thcap_receipt_channel_receiptID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_channel_receiptID_idx" ON thcap_receipt_channel USING btree ("receiptID");


--
-- TOC entry 3869 (class 1259 OID 67748)
-- Dependencies: 411 411
-- Name: thcap_receipt_channel_receiptID_recChannelType_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_channel_receiptID_recChannelType_idx" ON thcap_receipt_channel USING btree ("receiptID", "recChannelType");


--
-- TOC entry 3870 (class 1259 OID 67749)
-- Dependencies: 411 411 411
-- Name: thcap_receipt_channel_receiptID_recChannelType_recChannelRe_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_channel_receiptID_recChannelType_recChannelRe_idx" ON thcap_receipt_channel USING btree ("receiptID", "recChannelType", "recChannelRef");


--
-- TOC entry 3501 (class 1259 OID 67307)
-- Dependencies: 205
-- Name: thcap_receipt_contractID_idx; Type: INDEX; Schema: account; Owner: devgroup; Tablespace: 
--

CREATE INDEX "thcap_receipt_contractID_idx" ON thcap_receipt USING btree ("contractID");


--
-- TOC entry 3963 (class 1259 OID 69309)
-- Dependencies: 444
-- Name: thcap_receipt_details_rToInvItem_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_details_rToInvItem_idx" ON thcap_receipt_details USING btree ("rToInvoiceID");


--
-- TOC entry 3964 (class 1259 OID 69310)
-- Dependencies: 444 444
-- Name: thcap_receipt_details_recDetailsID_rToInvItem_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_details_recDetailsID_rToInvItem_idx" ON thcap_receipt_details USING btree ("recDetailsID", "rToInvoiceID");


--
-- TOC entry 3528 (class 1259 OID 66162)
-- Dependencies: 209
-- Name: thcap_receipt_details_receiptID_fkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_details_receiptID_fkey" ON thcap_mg_statement USING btree ("receiptID");


--
-- TOC entry 3965 (class 1259 OID 69311)
-- Dependencies: 444
-- Name: thcap_receipt_details_receiptID_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_details_receiptID_idx" ON thcap_receipt_details USING btree ("receiptID");


--
-- TOC entry 3968 (class 1259 OID 69312)
-- Dependencies: 444 444
-- Name: thcap_receipt_details_receiptID_rType_idx; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receipt_details_receiptID_rType_idx" ON thcap_receipt_details USING btree ("receiptID", futureuse01);


--
-- TOC entry 3502 (class 1259 OID 67757)
-- Dependencies: 205
-- Name: thcap_receipt_paperWHTID_idx; Type: INDEX; Schema: account; Owner: devgroup; Tablespace: 
--

CREATE INDEX "thcap_receipt_paperWHTID_idx" ON thcap_receipt USING btree ("paperWHTID");


--
-- TOC entry 3505 (class 1259 OID 67308)
-- Dependencies: 205
-- Name: thcap_receipt_receiveDate_idx; Type: INDEX; Schema: account; Owner: devgroup; Tablespace: 
--

CREATE INDEX "thcap_receipt_receiveDate_idx" ON thcap_receipt USING btree ("receiveDate");


--
-- TOC entry 3945 (class 1259 OID 69722)
-- Dependencies: 438
-- Name: thcap_typePay_fixed_tpid_pkey; Type: INDEX; Schema: account; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_typePay_fixed_tpid_pkey" ON "thcap_typePay_fixed" USING btree ("tpID");


SET search_path = carregis, pg_catalog;

--
-- TOC entry 3537 (class 1259 OID 65862)
-- Dependencies: 214
-- Name: CarTaxDue_IDNO_idx; Type: INDEX; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE INDEX "CarTaxDue_IDNO_idx" ON "CarTaxDue" USING btree ("IDNO");


--
-- TOC entry 3538 (class 1259 OID 65863)
-- Dependencies: 214
-- Name: CarTaxDue_TaxDueDate_idx; Type: INDEX; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE INDEX "CarTaxDue_TaxDueDate_idx" ON "CarTaxDue" USING btree ("TaxDueDate");


--
-- TOC entry 3541 (class 1259 OID 65864)
-- Dependencies: 215
-- Name: DetailCarTax_BillNumber_idx; Type: INDEX; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCarTax_BillNumber_idx" ON "DetailCarTax" USING btree ("BillNumber");


--
-- TOC entry 3542 (class 1259 OID 65865)
-- Dependencies: 215
-- Name: DetailCarTax_CoPayDate_idx; Type: INDEX; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCarTax_CoPayDate_idx" ON "DetailCarTax" USING btree ("CoPayDate");


--
-- TOC entry 3543 (class 1259 OID 65866)
-- Dependencies: 215
-- Name: DetailCarTax_IDCarTax_idx; Type: INDEX; Schema: carregis; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCarTax_IDCarTax_idx" ON "DetailCarTax" USING btree ("IDCarTax");


SET search_path = finance, pg_catalog;

--
-- TOC entry 3841 (class 1259 OID 66720)
-- Dependencies: 396
-- Name: thcap_receive_transfer_action_revTranID_fkey; Type: INDEX; Schema: finance; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receive_transfer_action_revTranID_fkey" ON thcap_receive_transfer_action USING btree ("revTranID");


--
-- TOC entry 3842 (class 1259 OID 66714)
-- Dependencies: 396
-- Name: thcap_receive_transfer_action_revTranID_idx; Type: INDEX; Schema: finance; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receive_transfer_action_revTranID_idx" ON thcap_receive_transfer_action USING btree ("revTranID");


--
-- TOC entry 3835 (class 1259 OID 66689)
-- Dependencies: 394 394
-- Name: thcap_receive_transfer_bankRevRef1_bankRevRef2_idx; Type: INDEX; Schema: finance; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receive_transfer_bankRevRef1_bankRevRef2_idx" ON thcap_receive_transfer USING btree ("bankRevRef1", "bankRevRef2");


--
-- TOC entry 3836 (class 1259 OID 66690)
-- Dependencies: 394
-- Name: thcap_receive_transfer_cnID_fkey; Type: INDEX; Schema: finance; Owner: dev; Tablespace: 
--

CREATE INDEX "thcap_receive_transfer_cnID_fkey" ON thcap_receive_transfer USING btree ("cnID");


SET search_path = insure, pg_catalog;

--
-- TOC entry 3919 (class 1259 OID 68888)
-- Dependencies: 431
-- Name: InsureForce_Backup_CarID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureForce_Backup_CarID_idx" ON "InsureForce_Backup" USING btree ("CarID");


--
-- TOC entry 3920 (class 1259 OID 68889)
-- Dependencies: 431
-- Name: InsureForce_Backup_CusID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureForce_Backup_CusID_idx" ON "InsureForce_Backup" USING btree ("CusID");


--
-- TOC entry 3921 (class 1259 OID 68890)
-- Dependencies: 431
-- Name: InsureForce_Backup_IDNO_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureForce_Backup_IDNO_idx" ON "InsureForce_Backup" USING btree ("IDNO");


--
-- TOC entry 3922 (class 1259 OID 68891)
-- Dependencies: 431
-- Name: InsureForce_Backup_InsID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureForce_Backup_InsID_idx" ON "InsureForce_Backup" USING btree ("InsID");


--
-- TOC entry 3574 (class 1259 OID 65867)
-- Dependencies: 234
-- Name: InsureForce_CarID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureForce_CarID_idx" ON "InsureForce" USING btree ("CarID");


--
-- TOC entry 3575 (class 1259 OID 65868)
-- Dependencies: 234
-- Name: InsureForce_CusID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureForce_CusID_idx" ON "InsureForce" USING btree ("CusID");


--
-- TOC entry 3576 (class 1259 OID 65869)
-- Dependencies: 234
-- Name: InsureForce_IDNO_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureForce_IDNO_idx" ON "InsureForce" USING btree ("IDNO");


--
-- TOC entry 3577 (class 1259 OID 65870)
-- Dependencies: 234
-- Name: InsureForce_InsID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureForce_InsID_idx" ON "InsureForce" USING btree ("InsID");


--
-- TOC entry 3925 (class 1259 OID 68910)
-- Dependencies: 432
-- Name: InsureUnforce_Backup_CarID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_Backup_CarID_idx" ON "InsureUnforce_Backup" USING btree ("CarID");


--
-- TOC entry 3926 (class 1259 OID 68911)
-- Dependencies: 432
-- Name: InsureUnforce_Backup_CusID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_Backup_CusID_idx" ON "InsureUnforce_Backup" USING btree ("CusID");


--
-- TOC entry 3927 (class 1259 OID 68912)
-- Dependencies: 432
-- Name: InsureUnforce_Backup_IDNO_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_Backup_IDNO_idx" ON "InsureUnforce_Backup" USING btree ("IDNO");


--
-- TOC entry 3928 (class 1259 OID 68913)
-- Dependencies: 432
-- Name: InsureUnforce_Backup_InsID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_Backup_InsID_idx" ON "InsureUnforce_Backup" USING btree ("InsID");


--
-- TOC entry 3929 (class 1259 OID 68914)
-- Dependencies: 432
-- Name: InsureUnforce_Backup_TempInsID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_Backup_TempInsID_idx" ON "InsureUnforce_Backup" USING btree ("TempInsID");


--
-- TOC entry 3584 (class 1259 OID 65871)
-- Dependencies: 237
-- Name: InsureUnforce_CarID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_CarID_idx" ON "InsureUnforce" USING btree ("CarID");


--
-- TOC entry 3585 (class 1259 OID 65872)
-- Dependencies: 237
-- Name: InsureUnforce_CusID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_CusID_idx" ON "InsureUnforce" USING btree ("CusID");


--
-- TOC entry 3586 (class 1259 OID 65873)
-- Dependencies: 237
-- Name: InsureUnforce_IDNO_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_IDNO_idx" ON "InsureUnforce" USING btree ("IDNO");


--
-- TOC entry 3587 (class 1259 OID 65874)
-- Dependencies: 237
-- Name: InsureUnforce_InsID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_InsID_idx" ON "InsureUnforce" USING btree ("InsID");


--
-- TOC entry 3588 (class 1259 OID 65875)
-- Dependencies: 237
-- Name: InsureUnforce_TempInsID_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX "InsureUnforce_TempInsID_idx" ON "InsureUnforce" USING btree ("TempInsID");


--
-- TOC entry 3605 (class 1259 OID 65876)
-- Dependencies: 246
-- Name: batch_id_idx; Type: INDEX; Schema: insure; Owner: dev; Tablespace: 
--

CREATE INDEX batch_id_idx ON batch USING btree (id);


SET search_path = public, pg_catalog;

--
-- TOC entry 3631 (class 1259 OID 65877)
-- Dependencies: 266
-- Name: AccPayment_DueDate_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "AccPayment_DueDate_idx" ON "AccPayment" USING btree ("DueDate");


--
-- TOC entry 3890 (class 1259 OID 68784)
-- Dependencies: 420
-- Name: ContactCus_Backup_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "ContactCus_Backup_CusID_idx" ON "ContactCus_Backup" USING btree ("CusID");


--
-- TOC entry 3612 (class 1259 OID 65878)
-- Dependencies: 251
-- Name: ContactCus_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "ContactCus_CusID_idx" ON "ContactCus" USING btree ("CusID");


--
-- TOC entry 3646 (class 1259 OID 65879)
-- Dependencies: 273
-- Name: CusPayment_DueDate_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "CusPayment_DueDate_idx" ON "CusPayment" USING btree ("DueDate");


--
-- TOC entry 3895 (class 1259 OID 68803)
-- Dependencies: 423
-- Name: DetailCheque_Backup_ChequeNo_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCheque_Backup_ChequeNo_idx" ON "DetailCheque_Backup" USING btree ("ChequeNo");


--
-- TOC entry 3896 (class 1259 OID 68804)
-- Dependencies: 423
-- Name: DetailCheque_Backup_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCheque_Backup_CusID_idx" ON "DetailCheque_Backup" USING btree ("CusID");


--
-- TOC entry 3897 (class 1259 OID 68805)
-- Dependencies: 423
-- Name: DetailCheque_Backup_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCheque_Backup_IDNO_idx" ON "DetailCheque_Backup" USING btree ("IDNO");


--
-- TOC entry 3898 (class 1259 OID 68806)
-- Dependencies: 423
-- Name: DetailCheque_Backup_PostID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCheque_Backup_PostID_idx" ON "DetailCheque_Backup" USING btree ("PostID");


--
-- TOC entry 3653 (class 1259 OID 65880)
-- Dependencies: 277
-- Name: DetailCheque_ChequeNo_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCheque_ChequeNo_idx" ON "DetailCheque" USING btree ("ChequeNo");


--
-- TOC entry 3654 (class 1259 OID 65881)
-- Dependencies: 277
-- Name: DetailCheque_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCheque_CusID_idx" ON "DetailCheque" USING btree ("CusID");


--
-- TOC entry 3655 (class 1259 OID 65882)
-- Dependencies: 277
-- Name: DetailCheque_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCheque_IDNO_idx" ON "DetailCheque" USING btree ("IDNO");


--
-- TOC entry 3656 (class 1259 OID 65883)
-- Dependencies: 277
-- Name: DetailCheque_PostID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailCheque_PostID_idx" ON "DetailCheque" USING btree ("PostID");


--
-- TOC entry 3659 (class 1259 OID 65884)
-- Dependencies: 279
-- Name: DetailTranpay_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailTranpay_IDNO_idx" ON "DetailTranpay" USING btree ("IDNO");


--
-- TOC entry 3660 (class 1259 OID 65885)
-- Dependencies: 279
-- Name: DetailTranpay_PostID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailTranpay_PostID_idx" ON "DetailTranpay" USING btree ("PostID");


--
-- TOC entry 3661 (class 1259 OID 65886)
-- Dependencies: 279
-- Name: DetailTranpay_ReceiptNo_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "DetailTranpay_ReceiptNo_idx" ON "DetailTranpay" USING btree ("ReceiptNo");


--
-- TOC entry 3901 (class 1259 OID 68818)
-- Dependencies: 425
-- Name: FCash_Backup_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FCash_Backup_CusID_idx" ON "FCash_Backup" USING btree ("CusID");


--
-- TOC entry 3902 (class 1259 OID 68819)
-- Dependencies: 425
-- Name: FCash_Backup_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FCash_Backup_IDNO_idx" ON "FCash_Backup" USING btree ("IDNO");


--
-- TOC entry 3903 (class 1259 OID 68820)
-- Dependencies: 425
-- Name: FCash_Backup_PostID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FCash_Backup_PostID_idx" ON "FCash_Backup" USING btree ("PostID");


--
-- TOC entry 3666 (class 1259 OID 65887)
-- Dependencies: 282
-- Name: FCash_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FCash_CusID_idx" ON "FCash" USING btree ("CusID");


--
-- TOC entry 3667 (class 1259 OID 65888)
-- Dependencies: 282
-- Name: FCash_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FCash_IDNO_idx" ON "FCash" USING btree ("IDNO");


--
-- TOC entry 3668 (class 1259 OID 65889)
-- Dependencies: 282
-- Name: FCash_PostID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FCash_PostID_idx" ON "FCash" USING btree ("PostID");


--
-- TOC entry 3673 (class 1259 OID 65890)
-- Dependencies: 285
-- Name: FGas_car_regis_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FGas_car_regis_idx" ON "FGas" USING btree (car_regis);


--
-- TOC entry 3674 (class 1259 OID 65891)
-- Dependencies: 285
-- Name: FGas_carnum_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FGas_carnum_idx" ON "FGas" USING btree (carnum);


--
-- TOC entry 3675 (class 1259 OID 65892)
-- Dependencies: 285
-- Name: FGas_marnum_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FGas_marnum_idx" ON "FGas" USING btree (marnum);


--
-- TOC entry 3558 (class 1259 OID 65893)
-- Dependencies: 225
-- Name: FOtherpay_O_DATE_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FOtherpay_O_DATE_idx" ON "FOtherpay" USING btree ("O_DATE");


--
-- TOC entry 3559 (class 1259 OID 65898)
-- Dependencies: 225
-- Name: FOtherpay_O_MONEY_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FOtherpay_O_MONEY_idx" ON "FOtherpay" USING btree ("O_MONEY");


--
-- TOC entry 3680 (class 1259 OID 65899)
-- Dependencies: 287
-- Name: FTACCheque_COID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FTACCheque_COID_idx" ON "FTACCheque" USING btree ("COID");


--
-- TOC entry 3681 (class 1259 OID 65900)
-- Dependencies: 287
-- Name: FTACCheque_D_ChequeNo_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FTACCheque_D_ChequeNo_idx" ON "FTACCheque" USING btree ("D_ChequeNo");


--
-- TOC entry 3682 (class 1259 OID 65901)
-- Dependencies: 287
-- Name: FTACCheque_PostID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FTACCheque_PostID_idx" ON "FTACCheque" USING btree ("PostID");


--
-- TOC entry 3685 (class 1259 OID 65902)
-- Dependencies: 289
-- Name: FTACTran_COID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FTACTran_COID_idx" ON "FTACTran" USING btree ("COID");


--
-- TOC entry 3686 (class 1259 OID 65903)
-- Dependencies: 289
-- Name: FTACTran_PostID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FTACTran_PostID_idx" ON "FTACTran" USING btree ("PostID");


--
-- TOC entry 3486 (class 1259 OID 65904)
-- Dependencies: 198
-- Name: FVat_V_Date_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FVat_V_Date_idx" ON "FVat" USING btree ("V_Date");


--
-- TOC entry 3595 (class 1259 OID 65905)
-- Dependencies: 240
-- Name: Fa1_A_NAME_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fa1_A_NAME_idx" ON "Fa1" USING btree ("A_NAME");


--
-- TOC entry 3596 (class 1259 OID 65906)
-- Dependencies: 240
-- Name: Fa1_A_SIRNAME_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fa1_A_SIRNAME_idx" ON "Fa1" USING btree ("A_SIRNAME");


--
-- TOC entry 3912 (class 1259 OID 68856)
-- Dependencies: 429
-- Name: Fa1_Backup_A_NAME_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fa1_Backup_A_NAME_idx" ON "Fa1_Backup" USING btree ("A_NAME");


--
-- TOC entry 3913 (class 1259 OID 68857)
-- Dependencies: 429
-- Name: Fa1_Backup_A_SIRNAME_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fa1_Backup_A_SIRNAME_idx" ON "Fa1_Backup" USING btree ("A_SIRNAME");


--
-- TOC entry 3934 (class 1259 OID 68959)
-- Dependencies: 435
-- Name: Fa1_temp_A_NAME_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fa1_temp_A_NAME_idx" ON "Fa1_temp" USING btree ("A_NAME");


--
-- TOC entry 3935 (class 1259 OID 68960)
-- Dependencies: 435
-- Name: Fa1_temp_A_SIRNAME_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fa1_temp_A_SIRNAME_idx" ON "Fa1_temp" USING btree ("A_SIRNAME");


--
-- TOC entry 3599 (class 1259 OID 65907)
-- Dependencies: 241
-- Name: Fc_C_CARNUM_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fc_C_CARNUM_idx" ON "Fc" USING btree ("C_CARNUM");


--
-- TOC entry 3600 (class 1259 OID 65908)
-- Dependencies: 241
-- Name: Fc_C_MARNUM_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fc_C_MARNUM_idx" ON "Fc" USING btree ("C_MARNUM");


--
-- TOC entry 3601 (class 1259 OID 65909)
-- Dependencies: 241
-- Name: Fc_C_REGIS_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fc_C_REGIS_idx" ON "Fc" USING btree ("C_REGIS");


--
-- TOC entry 3602 (class 1259 OID 65910)
-- Dependencies: 241
-- Name: Fc_C_StartDate_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fc_C_StartDate_idx" ON "Fc" USING btree ("C_StartDate");


--
-- TOC entry 3916 (class 1259 OID 68868)
-- Dependencies: 430
-- Name: Fn_Backup_N_IDCARD_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fn_Backup_N_IDCARD_idx" ON "Fn_Backup" USING btree ("N_IDCARD");


--
-- TOC entry 3689 (class 1259 OID 65911)
-- Dependencies: 291
-- Name: Fn_N_IDCARD_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fn_N_IDCARD_idx" ON "Fn" USING btree ("N_IDCARD");


--
-- TOC entry 3938 (class 1259 OID 68971)
-- Dependencies: 436
-- Name: Fn_temp_N_IDCARD_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fn_temp_N_IDCARD_idx" ON "Fn_temp" USING btree ("N_IDCARD");


--
-- TOC entry 3906 (class 1259 OID 68832)
-- Dependencies: 427
-- Name: FollowUpCus_Backup_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FollowUpCus_Backup_CusID_idx" ON "FollowUpCus_Backup" USING btree ("CusID");


--
-- TOC entry 3907 (class 1259 OID 68833)
-- Dependencies: 427
-- Name: FollowUpCus_Backup_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FollowUpCus_Backup_IDNO_idx" ON "FollowUpCus_Backup" USING btree ("IDNO");


--
-- TOC entry 3692 (class 1259 OID 65912)
-- Dependencies: 292
-- Name: FollowUpCus_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FollowUpCus_CusID_idx" ON "FollowUpCus" USING btree ("CusID");


--
-- TOC entry 3693 (class 1259 OID 65915)
-- Dependencies: 292
-- Name: FollowUpCus_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "FollowUpCus_IDNO_idx" ON "FollowUpCus" USING btree ("IDNO");


--
-- TOC entry 3885 (class 1259 OID 68775)
-- Dependencies: 419
-- Name: Fp_Backup_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fp_Backup_CusID_idx" ON "Fp_Backup" USING btree ("CusID");


--
-- TOC entry 3886 (class 1259 OID 68776)
-- Dependencies: 419
-- Name: Fp_Backup_P_STDATE_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fp_Backup_P_STDATE_idx" ON "Fp_Backup" USING btree ("P_STDATE");


--
-- TOC entry 3887 (class 1259 OID 68777)
-- Dependencies: 419
-- Name: Fp_Backup_asset_id_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fp_Backup_asset_id_idx" ON "Fp_Backup" USING btree (asset_id);


--
-- TOC entry 3489 (class 1259 OID 65924)
-- Dependencies: 199
-- Name: Fp_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fp_CusID_idx" ON "Fp" USING btree ("CusID");


--
-- TOC entry 3490 (class 1259 OID 65925)
-- Dependencies: 199
-- Name: Fp_P_STDATE_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fp_P_STDATE_idx" ON "Fp" USING btree ("P_STDATE");


--
-- TOC entry 3874 (class 1259 OID 68030)
-- Dependencies: 417
-- Name: Fp_TestMigrate_CusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fp_TestMigrate_CusID_idx" ON "Fp_TestMigrate" USING btree ("CusID");


--
-- TOC entry 3875 (class 1259 OID 68031)
-- Dependencies: 417
-- Name: Fp_TestMigrate_P_STDATE_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fp_TestMigrate_P_STDATE_idx" ON "Fp_TestMigrate" USING btree ("P_STDATE");


--
-- TOC entry 3876 (class 1259 OID 68032)
-- Dependencies: 417
-- Name: Fp_TestMigrate_asset_id_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fp_TestMigrate_asset_id_idx" ON "Fp_TestMigrate" USING btree (asset_id);


--
-- TOC entry 3491 (class 1259 OID 65926)
-- Dependencies: 199
-- Name: Fp_asset_id_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fp_asset_id_idx" ON "Fp" USING btree (asset_id);


--
-- TOC entry 3494 (class 1259 OID 65927)
-- Dependencies: 200
-- Name: Fr_R_Date_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "Fr_R_Date_idx" ON "Fr" USING btree ("R_Date");


--
-- TOC entry 3706 (class 1259 OID 65928)
-- Dependencies: 298
-- Name: LogsAnyFunction_ref_id_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "LogsAnyFunction_ref_id_idx" ON "LogsAnyFunction" USING btree (ref_id);


--
-- TOC entry 3707 (class 1259 OID 65929)
-- Dependencies: 298 298
-- Name: LogsAnyFunction_time_open_time_close_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "LogsAnyFunction_time_open_time_close_idx" ON "LogsAnyFunction" USING btree (time_open, time_close);


--
-- TOC entry 3710 (class 1259 OID 65930)
-- Dependencies: 301
-- Name: NTDetail_NTID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "NTDetail_NTID_idx" ON "NTDetail" USING btree ("NTID");


--
-- TOC entry 3713 (class 1259 OID 65931)
-- Dependencies: 303
-- Name: NTHead_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "NTHead_IDNO_idx" ON "NTHead" USING btree ("IDNO");


--
-- TOC entry 3720 (class 1259 OID 65932)
-- Dependencies: 306
-- Name: PostLog_PostDate_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "PostLog_PostDate_idx" ON "PostLog" USING btree ("PostDate");


--
-- TOC entry 3728 (class 1259 OID 65933)
-- Dependencies: 308
-- Name: RadioContract_Bin_RadioCar_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "RadioContract_Bin_RadioCar_idx" ON "RadioContract_Bin" USING btree ("RadioCar");


--
-- TOC entry 3729 (class 1259 OID 65934)
-- Dependencies: 308
-- Name: RadioContract_Bin_RadioNum_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "RadioContract_Bin_RadioNum_idx" ON "RadioContract_Bin" USING btree ("RadioNum");


--
-- TOC entry 3730 (class 1259 OID 65935)
-- Dependencies: 308
-- Name: RadioContract_Bin_RadioRelationID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "RadioContract_Bin_RadioRelationID_idx" ON "RadioContract_Bin" USING btree ("RadioRelationID");


--
-- TOC entry 3723 (class 1259 OID 65936)
-- Dependencies: 307
-- Name: RadioContract_RadioCar_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "RadioContract_RadioCar_idx" ON "RadioContract" USING btree ("RadioCar");


--
-- TOC entry 3724 (class 1259 OID 65937)
-- Dependencies: 307
-- Name: RadioContract_RadioNum_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "RadioContract_RadioNum_idx" ON "RadioContract" USING btree ("RadioNum");


--
-- TOC entry 3725 (class 1259 OID 65938)
-- Dependencies: 307
-- Name: RadioContract_RadioRelationID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "RadioContract_RadioRelationID_idx" ON "RadioContract" USING btree ("RadioRelationID");


--
-- TOC entry 3733 (class 1259 OID 65939)
-- Dependencies: 309
-- Name: TacMail_tmCusID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "TacMail_tmCusID_idx" ON "TacMail" USING btree ("tmCusID");


--
-- TOC entry 3734 (class 1259 OID 65940)
-- Dependencies: 309
-- Name: TacMail_tmDoc_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "TacMail_tmDoc_idx" ON "TacMail" USING btree ("tmDoc");


--
-- TOC entry 3737 (class 1259 OID 65941)
-- Dependencies: 311
-- Name: TranPay_PostID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "TranPay_PostID_idx" ON "TranPay" USING btree ("PostID");


--
-- TOC entry 3740 (class 1259 OID 65942)
-- Dependencies: 311 311
-- Name: TranPay_ref1_ref2_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "TranPay_ref1_ref2_idx" ON "TranPay" USING btree (ref1, ref2);


--
-- TOC entry 3765 (class 1259 OID 65943)
-- Dependencies: 351
-- Name: logs_NTDetail_NTID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "logs_NTDetail_NTID_idx" ON "logs_NTDetail" USING btree ("NTID");


--
-- TOC entry 3770 (class 1259 OID 65944)
-- Dependencies: 355
-- Name: logs_nw_regis_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "logs_nw_regis_IDNO_idx" ON logs_nw_regis USING btree ("IDNO");


--
-- TOC entry 3771 (class 1259 OID 65945)
-- Dependencies: 355
-- Name: logs_nw_regis_asset_id_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX logs_nw_regis_asset_id_idx ON logs_nw_regis USING btree (asset_id);


--
-- TOC entry 3780 (class 1259 OID 65946)
-- Dependencies: 360
-- Name: nw_annouceuser_id_user_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX nw_annouceuser_id_user_idx ON nw_annouceuser USING btree (id_user);


--
-- TOC entry 3797 (class 1259 OID 65947)
-- Dependencies: 372
-- Name: nw_statusNT_IDNO_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "nw_statusNT_IDNO_idx" ON "nw_statusNT" USING btree ("IDNO");


--
-- TOC entry 3798 (class 1259 OID 65948)
-- Dependencies: 372
-- Name: nw_statusNT_NTID_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX "nw_statusNT_NTID_idx" ON "nw_statusNT" USING btree ("NTID");


--
-- TOC entry 3809 (class 1259 OID 65949)
-- Dependencies: 377
-- Name: tac_old_nt_tac_cusid_idx; Type: INDEX; Schema: public; Owner: dev; Tablespace: 
--

CREATE INDEX tac_old_nt_tac_cusid_idx ON tac_old_nt USING btree (tac_cusid);


SET search_path = account, pg_catalog;

--
-- TOC entry 3122 (class 2618 OID 69699)
-- Dependencies: 444 444 444
-- Name: thcap_receipt_details_noUpdate; Type: RULE; Schema: account; Owner: dev
--

CREATE RULE "thcap_receipt_details_noUpdate" AS ON UPDATE TO thcap_receipt_details DO INSTEAD NOTHING;


--
-- TOC entry 4734 (class 0 OID 0)
-- Dependencies: 3122
-- Name: RULE "thcap_receipt_details_noUpdate" ON thcap_receipt_details; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON RULE "thcap_receipt_details_noUpdate" ON thcap_receipt_details IS 'ห้าม UPDATE TABLE นี้ การ UPDATE จะเกิดคือ ไม่มีอะไรเกิดขึ้น';


--
-- TOC entry 4004 (class 2620 OID 69706)
-- Dependencies: 584 205 205
-- Name: cancelReceiptTG; Type: TRIGGER; Schema: account; Owner: devgroup
--

CREATE TRIGGER "cancelReceiptTG" AFTER UPDATE ON thcap_receipt FOR EACH ROW WHEN (((old."receiptStatus" = 1) AND (new."receiptStatus" = 0))) EXECUTE PROCEDURE "thcap_mg_cancelReceiptTG"();


--
-- TOC entry 4735 (class 0 OID 0)
-- Dependencies: 4004
-- Name: TRIGGER "cancelReceiptTG" ON thcap_receipt; Type: COMMENT; Schema: account; Owner: devgroup
--

COMMENT ON TRIGGER "cancelReceiptTG" ON thcap_receipt IS 'clear เหตุการณ์ต่างๆที่เกิดจากใบเสร็จ ใบที่ถูก mask ว่ายกเลิก ให้กลับเป็นเหมือนยังไม่จ่าย ยกเว้น details การจ่าย

โดนจะรันเฉพาะเปลี่ยนจาก 1 (ACTIVE) -> 0 (ยกเลิก)

กรณียกเลิกอยู่แล้ว และโดน UPDATE เป็น 0
(0 -> 0) จะไม่ run trigger นี้เนื่องจากจะเกิดปัญหา';


--
-- TOC entry 4005 (class 2620 OID 69698)
-- Dependencies: 444 583
-- Name: receiptPayToInv; Type: TRIGGER; Schema: account; Owner: dev
--

CREATE TRIGGER "receiptPayToInv" AFTER INSERT ON thcap_receipt_details FOR EACH ROW EXECUTE PROCEDURE "thcap_mg_receiptPayToInv"();


--
-- TOC entry 4736 (class 0 OID 0)
-- Dependencies: 4005
-- Name: TRIGGER "receiptPayToInv" ON thcap_receipt_details; Type: COMMENT; Schema: account; Owner: dev
--

COMMENT ON TRIGGER "receiptPayToInv" ON thcap_receipt_details IS 'Update หนี้ค้างจ่ายใน account.thcap_invoice อัตโนมัติ';


--
-- TOC entry 4006 (class 2620 OID 69696)
-- Dependencies: 445 582
-- Name: recreateMainInvPT; Type: TRIGGER; Schema: account; Owner: dev
--

CREATE TRIGGER "recreateMainInvPT" AFTER DELETE ON thcap_mg_invoice_payterm FOR EACH ROW EXECUTE PROCEDURE "thcap_mg_recreateMainInvPT"();


--
-- TOC entry 3997 (class 2606 OID 68501)
-- Dependencies: 3883 416 418
-- Name: thcap_dncn_action_dcNoteID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_dncn_action
    ADD CONSTRAINT "thcap_dncn_action_dcNoteID_fkey" FOREIGN KEY ("dcNoteID") REFERENCES thcap_dncn("dcNoteID");


--
-- TOC entry 3998 (class 2606 OID 69162)
-- Dependencies: 418 441 3956
-- Name: thcap_dncn_invoiceID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_dncn
    ADD CONSTRAINT "thcap_dncn_invoiceID_fkey" FOREIGN KEY ("invoiceID") REFERENCES thcap_invoice("invoiceID");


--
-- TOC entry 3993 (class 2606 OID 69136)
-- Dependencies: 3956 408 441
-- Name: thcap_invoice_action_invoiceID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_invoice_action
    ADD CONSTRAINT "thcap_invoice_action_invoiceID_fkey" FOREIGN KEY ("invoiceID") REFERENCES thcap_invoice("invoiceID");


--
-- TOC entry 4000 (class 2606 OID 69728)
-- Dependencies: 441 453 3984
-- Name: thcap_invoice_invoiceTypePay_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_invoice
    ADD CONSTRAINT "thcap_invoice_invoiceTypePay_fkey" FOREIGN KEY ("invoiceTypePay") REFERENCES "thcap_typePay"("tpID");


--
-- TOC entry 3986 (class 2606 OID 66169)
-- Dependencies: 204 205 3503
-- Name: thcap_mg_receipt_interest_receiptID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_mg_receipt_interest
    ADD CONSTRAINT "thcap_mg_receipt_interest_receiptID_fkey" FOREIGN KEY ("receiptID") REFERENCES thcap_receipt("receiptID");


--
-- TOC entry 4001 (class 2606 OID 69263)
-- Dependencies: 205 442 3503
-- Name: thcap_mg_receipt_principle_receiptID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_mg_receipt_principle
    ADD CONSTRAINT "thcap_mg_receipt_principle_receiptID_fkey" FOREIGN KEY ("receiptID") REFERENCES thcap_receipt("receiptID");


--
-- TOC entry 3987 (class 2606 OID 66157)
-- Dependencies: 209 205 3503
-- Name: thcap_mg_statement_receiptID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_mg_statement
    ADD CONSTRAINT "thcap_mg_statement_receiptID_fkey" FOREIGN KEY ("receiptID") REFERENCES thcap_receipt("receiptID");


--
-- TOC entry 3992 (class 2606 OID 67356)
-- Dependencies: 205 3503 406
-- Name: thcap_receipt_action_receiptID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_receipt_action
    ADD CONSTRAINT "thcap_receipt_action_receiptID_fkey" FOREIGN KEY ("receiptID") REFERENCES thcap_receipt("receiptID");


--
-- TOC entry 3996 (class 2606 OID 67751)
-- Dependencies: 411 3827 388
-- Name: thcap_receipt_channel_recChannelType_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_receipt_channel
    ADD CONSTRAINT "thcap_receipt_channel_recChannelType_fkey" FOREIGN KEY ("recChannelType") REFERENCES thcap_channel("channelID");


--
-- TOC entry 3995 (class 2606 OID 67742)
-- Dependencies: 205 411 3503
-- Name: thcap_receipt_channel_receiptID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_receipt_channel
    ADD CONSTRAINT "thcap_receipt_channel_receiptID_fkey" FOREIGN KEY ("receiptID") REFERENCES thcap_receipt("receiptID");


--
-- TOC entry 3994 (class 2606 OID 67498)
-- Dependencies: 205 3503 409
-- Name: thcap_receipt_desc_receiptID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_receipt_desc
    ADD CONSTRAINT "thcap_receipt_desc_receiptID_fkey" FOREIGN KEY ("receiptID") REFERENCES thcap_receipt("receiptID");


--
-- TOC entry 4002 (class 2606 OID 69700)
-- Dependencies: 441 444 3956
-- Name: thcap_receipt_details_rToInvoiceID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_receipt_details
    ADD CONSTRAINT "thcap_receipt_details_rToInvoiceID_fkey" FOREIGN KEY ("rToInvoiceID") REFERENCES thcap_invoice("invoiceID");


--
-- TOC entry 4003 (class 2606 OID 69304)
-- Dependencies: 444 3503 205
-- Name: thcap_receipt_details_receiptID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY thcap_receipt_details
    ADD CONSTRAINT "thcap_receipt_details_receiptID_fkey" FOREIGN KEY ("receiptID") REFERENCES thcap_receipt("receiptID");


--
-- TOC entry 3999 (class 2606 OID 69717)
-- Dependencies: 453 3984 438
-- Name: thcap_typePay_fixed_tpID_fkey; Type: FK CONSTRAINT; Schema: account; Owner: dev
--

ALTER TABLE ONLY "thcap_typePay_fixed"
    ADD CONSTRAINT "thcap_typePay_fixed_tpID_fkey" FOREIGN KEY ("tpID") REFERENCES "thcap_typePay"("tpID");


SET search_path = finance, pg_catalog;

--
-- TOC entry 3991 (class 2606 OID 66823)
-- Dependencies: 3827 388 397
-- Name: thcap_receive_cheque_cnID_fkey; Type: FK CONSTRAINT; Schema: finance; Owner: dev
--

ALTER TABLE ONLY thcap_receive_cheque
    ADD CONSTRAINT "thcap_receive_cheque_cnID_fkey" FOREIGN KEY ("cnID") REFERENCES account.thcap_channel("channelID");


--
-- TOC entry 3990 (class 2606 OID 66715)
-- Dependencies: 394 396 3837
-- Name: thcap_receive_transfer_action_revTranID_fkey; Type: FK CONSTRAINT; Schema: finance; Owner: dev
--

ALTER TABLE ONLY thcap_receive_transfer_action
    ADD CONSTRAINT "thcap_receive_transfer_action_revTranID_fkey" FOREIGN KEY ("revTranID") REFERENCES thcap_receive_transfer("revTranID");


--
-- TOC entry 3989 (class 2606 OID 66684)
-- Dependencies: 3827 394 388
-- Name: thcap_receive_transfer_cnID_fkey; Type: FK CONSTRAINT; Schema: finance; Owner: dev
--

ALTER TABLE ONLY thcap_receive_transfer
    ADD CONSTRAINT "thcap_receive_transfer_cnID_fkey" FOREIGN KEY ("cnID") REFERENCES account.thcap_channel("channelID");


SET search_path = refinance, pg_catalog;

--
-- TOC entry 3988 (class 2606 OID 65950)
-- Dependencies: 3812 383 381
-- Name: match_invite_inviteID_fkey; Type: FK CONSTRAINT; Schema: refinance; Owner: dev
--

ALTER TABLE ONLY match_invite
    ADD CONSTRAINT "match_invite_inviteID_fkey" FOREIGN KEY ("inviteID") REFERENCES invite("inviteID");


--
-- TOC entry 4011 (class 0 OID 0)
-- Dependencies: 15
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


SET search_path = account, pg_catalog;

--
-- TOC entry 4019 (class 0 OID 0)
-- Dependencies: 584
-- Name: thcap_mg_cancelReceiptTG(); Type: ACL; Schema: account; Owner: postgres
--

REVOKE ALL ON FUNCTION "thcap_mg_cancelReceiptTG"() FROM PUBLIC;
REVOKE ALL ON FUNCTION "thcap_mg_cancelReceiptTG"() FROM postgres;
GRANT ALL ON FUNCTION "thcap_mg_cancelReceiptTG"() TO devgroup;


--
-- TOC entry 4026 (class 0 OID 0)
-- Dependencies: 583
-- Name: thcap_mg_receiptPayToInv(); Type: ACL; Schema: account; Owner: postgres
--

REVOKE ALL ON FUNCTION "thcap_mg_receiptPayToInv"() FROM PUBLIC;
REVOKE ALL ON FUNCTION "thcap_mg_receiptPayToInv"() FROM postgres;
GRANT ALL ON FUNCTION "thcap_mg_receiptPayToInv"() TO postgres;
GRANT ALL ON FUNCTION "thcap_mg_receiptPayToInv"() TO devgroup;


--
-- TOC entry 4028 (class 0 OID 0)
-- Dependencies: 582
-- Name: thcap_mg_recreateMainInvPT(); Type: ACL; Schema: account; Owner: postgres
--

REVOKE ALL ON FUNCTION "thcap_mg_recreateMainInvPT"() FROM PUBLIC;
REVOKE ALL ON FUNCTION "thcap_mg_recreateMainInvPT"() FROM postgres;
GRANT ALL ON FUNCTION "thcap_mg_recreateMainInvPT"() TO postgres;
GRANT ALL ON FUNCTION "thcap_mg_recreateMainInvPT"() TO devgroup;


--
-- TOC entry 4077 (class 0 OID 0)
-- Dependencies: 205
-- Name: thcap_receipt; Type: ACL; Schema: account; Owner: devgroup
--

REVOKE ALL ON TABLE thcap_receipt FROM PUBLIC;
REVOKE ALL ON TABLE thcap_receipt FROM devgroup;
GRANT ALL ON TABLE thcap_receipt TO devgroup;


-- Completed on 2012-02-13 11:00:42

--
-- PostgreSQL database dump complete
--

