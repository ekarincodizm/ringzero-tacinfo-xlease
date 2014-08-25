-- Table: ta_join_main

DROP TABLE ta_join_main;

CREATE TABLE ta_join_main
(
  id serial NOT NULL,
  ta_join_pm_id character varying(50), -- รหัสการชำระค่าเข้าร่วม
  car_license character varying(50), -- เลขทะเบียนรถ
  carid character varying(12),
  car_license_seq smallint NOT NULL DEFAULT (0)::smallint, -- ลำดับการโอน ขายคืน รถยึด ฯ  /1 /2
  idno character varying(50), -- รหัสสัญญา
  cusid character varying(12),
  cpro_name character varying(255), -- ชื่อ-นามสกุลลูกค้า
  start_pay_date date, -- เดือนที่เริ่มเก็บค่าเข้าร่วม
  staff_check smallint NOT NULL DEFAULT (0)::smallint,
  cancel smallint NOT NULL DEFAULT (0)::smallint, -- ยกเลิกแล้วหรือไม่  1= ยกเลิก
  cancel_datetime timestamp without time zone, -- วันเวลาที่ยกเลิก
  note text, -- หมายเหตุ
  create_datetime timestamp without time zone, -- วันเวลาที่สร้างรายการ
  create_by character varying(10), -- พนักงานที่สร้างรายการ
  update_datetime timestamp without time zone, -- วันเวลาที่แก้ไขรายการ
  update_by character varying(10), -- พนักงานที่แก้ไขรายการ
  deleted smallint NOT NULL DEFAULT (0)::smallint, -- ลบแล้วหรือไม่?
  delete_datetime timestamp without time zone, -- วันเวลาที่ลบ
  delete_by character varying(10), -- พนักงานที่ลบ
  address text, -- ที่อยู่ของลูกค้าเข้าร่วม
  addr_user character varying(10), -- user ล่าสุดที่เพิ่มหรือแก้ไขที่อยู่
  addr_stamp timestamp without time zone, -- วันที่และเวลาที่เพิ่มหรือแก้ไขที่อยู่ล่าสุด
  approve_status smallint NOT NULL DEFAULT (0)::smallint, -- 1 =  รออนุมัติการเพิ่มข้อมูลใหม่...
  approver character varying(10), -- ผู้อนุมัติ
  approve_dt timestamp without time zone, -- วันเวลาที่อนุมัติ/ไม่อนุมัติ
  CONSTRAINT ta_join_main_pkey PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ta_join_main
  OWNER TO dev;
COMMENT ON COLUMN ta_join_main.ta_join_pm_id IS 'รหัสการชำระค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_main.car_license IS 'เลขทะเบียนรถ';
COMMENT ON COLUMN ta_join_main.car_license_seq IS 'ลำดับการโอน ขายคืน รถยึด ฯ  /1 /2';
COMMENT ON COLUMN ta_join_main.idno IS 'รหัสสัญญา';
COMMENT ON COLUMN ta_join_main.cpro_name IS 'ชื่อ-นามสกุลลูกค้า';
COMMENT ON COLUMN ta_join_main.start_pay_date IS 'เดือนที่เริ่มเก็บค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_main.cancel IS 'ยกเลิกแล้วหรือไม่  1= ยกเลิก';
COMMENT ON COLUMN ta_join_main.cancel_datetime IS 'วันเวลาที่ยกเลิก';
COMMENT ON COLUMN ta_join_main.note IS 'หมายเหตุ';
COMMENT ON COLUMN ta_join_main.create_datetime IS 'วันเวลาที่สร้างรายการ';
COMMENT ON COLUMN ta_join_main.create_by IS 'พนักงานที่สร้างรายการ';
COMMENT ON COLUMN ta_join_main.update_datetime IS 'วันเวลาที่แก้ไขรายการ';
COMMENT ON COLUMN ta_join_main.update_by IS 'พนักงานที่แก้ไขรายการ';
COMMENT ON COLUMN ta_join_main.deleted IS 'ลบแล้วหรือไม่?';
COMMENT ON COLUMN ta_join_main.delete_datetime IS 'วันเวลาที่ลบ';
COMMENT ON COLUMN ta_join_main.delete_by IS 'พนักงานที่ลบ';
COMMENT ON COLUMN ta_join_main.address IS 'ที่อยู่ของลูกค้าเข้าร่วม';
COMMENT ON COLUMN ta_join_main.addr_user IS 'ล่าสุดที่เพิ่มหรือแก้ไขที่อยู่';
COMMENT ON COLUMN ta_join_main.addr_stamp IS 'วันที่และเวลาที่เพิ่มหรือแก้ไขที่อยู่ล่าสุด';
COMMENT ON COLUMN ta_join_main.approve_status IS '1 =  รออนุมัติการเพิ่มข้อมูลใหม่
2  = รออนุมัติการแก้ไขข้อมูล
3  = อนุมัติ
4  = ไม่อนุมัติ';
COMMENT ON COLUMN ta_join_main.approver IS 'ผู้อนุมัติ';
COMMENT ON COLUMN ta_join_main.approve_dt IS 'วันเวลาที่อนุมัติ/ไม่อนุมัติ';

-- Index: car_license

DROP INDEX car_license;

CREATE INDEX car_license
  ON ta_join_main
  USING btree
  (car_license COLLATE pg_catalog."default" );

-- Index: cpro_name

DROP INDEX cpro_name;

CREATE INDEX cpro_name
  ON ta_join_main
  USING btree
  (cpro_name COLLATE pg_catalog."default" );

-- Index: idno

DROP INDEX idno;

CREATE INDEX idno
  ON ta_join_main
  USING btree
  (idno COLLATE pg_catalog."default" );

-- Index: ta_join_pm_id

DROP INDEX ta_join_pm_id;

CREATE INDEX ta_join_pm_id
  ON ta_join_main
  USING btree
  (ta_join_pm_id COLLATE pg_catalog."default" );

-- Table: ta_join_main_bin

DROP TABLE ta_join_main_bin;

CREATE TABLE ta_join_main_bin
(
  id serial NOT NULL,
  ta_join_pm_id character varying(50), -- รหัสการชำระค่าเข้าร่วม
  car_license character varying(50), -- เลขทะเบียนรถ
  carid character varying(12),
  car_license_seq smallint NOT NULL DEFAULT (0)::smallint, -- ลำดับการโอน ขายคืน รถยึด ฯ  /1 /2
  idno character varying(50), -- รหัสสัญญา
  cusid character varying(12),
  cpro_name character varying(255), -- ชื่อ-นามสกุลลูกค้า
  start_pay_date date, -- เดือนที่เริ่มเก็บค่าเข้าร่วม
  staff_check smallint NOT NULL DEFAULT (0)::smallint,
  cancel smallint NOT NULL DEFAULT (0)::smallint, -- ยกเลิกแล้วหรือไม่  1= ยกเลิก
  cancel_datetime timestamp without time zone, -- วันเวลาที่ยกเลิก
  note text, -- หมายเหตุ
  create_datetime timestamp without time zone, -- วันเวลาที่สร้างรายการ
  create_by character varying(10), -- พนักงานที่สร้างรายการ
  update_datetime timestamp without time zone, -- วันเวลาที่แก้ไขรายการ
  update_by character varying(10), -- พนักงานที่แก้ไขรายการ
  deleted smallint NOT NULL DEFAULT (0)::smallint, -- ลบแล้วหรือไม่?
  delete_datetime timestamp without time zone, -- วันเวลาที่ลบ
  delete_by character varying(10), -- พนักงานที่ลบ
  address text, -- ที่อยู่ของลูกค้าเข้าร่วม
  addr_user character varying(10), -- user ล่าสุดที่เพิ่มหรือแก้ไขที่อยู่
  addr_stamp timestamp without time zone, -- วันที่และเวลาที่เพิ่มหรือแก้ไขที่อยู่ล่าสุด
  approve_status smallint NOT NULL DEFAULT (0)::smallint, -- 1 =  รออนุมัติการเพิ่มข้อมูลใหม่...
  approver character varying(10), -- ผู้อนุมัติ
  approve_dt timestamp without time zone -- วันเวลาที่อนุมัติ/ไม่อนุมัติ
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ta_join_main_bin
  OWNER TO dev;
COMMENT ON COLUMN ta_join_main_bin.ta_join_pm_id IS 'รหัสการชำระค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_main_bin.car_license IS 'เลขทะเบียนรถ';
COMMENT ON COLUMN ta_join_main_bin.car_license_seq IS 'ลำดับการโอน ขายคืน รถยึด ฯ  /1 /2';
COMMENT ON COLUMN ta_join_main_bin.idno IS 'รหัสสัญญา';
COMMENT ON COLUMN ta_join_main_bin.cpro_name IS 'ชื่อ-นามสกุลลูกค้า';
COMMENT ON COLUMN ta_join_main_bin.start_pay_date IS 'เดือนที่เริ่มเก็บค่าเข้าร่วม';

COMMENT ON COLUMN ta_join_main_bin.cancel IS 'ยกเลิกแล้วหรือไม่  1= ยกเลิก';
COMMENT ON COLUMN ta_join_main_bin.cancel_datetime IS 'วันเวลาที่ยกเลิก';
COMMENT ON COLUMN ta_join_main_bin.note IS 'หมายเหตุ';
COMMENT ON COLUMN ta_join_main_bin.create_datetime IS 'วันเวลาที่สร้างรายการ';
COMMENT ON COLUMN ta_join_main_bin.create_by IS 'พนักงานที่สร้างรายการ';
COMMENT ON COLUMN ta_join_main_bin.update_datetime IS 'วันเวลาที่แก้ไขรายการ';
COMMENT ON COLUMN ta_join_main_bin.update_by IS 'พนักงานที่แก้ไขรายการ';
COMMENT ON COLUMN ta_join_main_bin.deleted IS 'ลบแล้วหรือไม่?';
COMMENT ON COLUMN ta_join_main_bin.delete_datetime IS 'วันเวลาที่ลบ';
COMMENT ON COLUMN ta_join_main_bin.delete_by IS 'พนักงานที่ลบ';
COMMENT ON COLUMN ta_join_main_bin.address IS 'ที่อยู่ของลูกค้าเข้าร่วม';
COMMENT ON COLUMN ta_join_main_bin.addr_user IS 'ล่าสุดที่เพิ่มหรือแก้ไขที่อยู่';
COMMENT ON COLUMN ta_join_main_bin.addr_stamp IS 'วันที่และเวลาที่เพิ่มหรือแก้ไขที่อยู่ล่าสุด';
COMMENT ON COLUMN ta_join_main_bin.approve_status IS '1 =  รออนุมัติการเพิ่มข้อมูลใหม่
2  = รออนุมัติการแก้ไขข้อมูล
3  = อนุมัติ
4  = ไม่อนุมัติ';
COMMENT ON COLUMN ta_join_main_bin.approver IS 'ผู้อนุมัติ';
COMMENT ON COLUMN ta_join_main_bin.approve_dt IS 'วันเวลาที่อนุมัติ/ไม่อนุมัติ';

-- Index: car_license2

DROP INDEX car_license2;

CREATE INDEX car_license2
  ON ta_join_main_bin
  USING btree
  (car_license COLLATE pg_catalog."default" );

-- Index: cpro_name2

DROP INDEX cpro_name2;

CREATE INDEX cpro_name2
  ON ta_join_main_bin
  USING btree
  (cpro_name COLLATE pg_catalog."default" );

-- Index: idno2

DROP INDEX idno2;

CREATE INDEX idno2
  ON ta_join_main_bin
  USING btree
  (idno COLLATE pg_catalog."default" );

-- Index: ta_join_pm_id2

DROP INDEX ta_join_pm_id2;

CREATE INDEX ta_join_pm_id2
  ON ta_join_main_bin
  USING btree
  (ta_join_pm_id COLLATE pg_catalog."default" );

-- Table: ta_join_main_car

DROP TABLE ta_join_main_car;

CREATE TABLE ta_join_main_car
(
  id integer,
  "CarID" character varying(12),
  car_license character varying(255),
  contract_id character varying(255),
  id_body character varying(255)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ta_join_main_car
  OWNER TO dev;
-- Table: ta_join_main_cus

DROP TABLE ta_join_main_cus;

CREATE TABLE ta_join_main_cus
(
  id integer,
  "CusID" character varying(12),
  fname character varying(255),
  lname character varying(255)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ta_join_main_cus
  OWNER TO dev;
-- Table: ta_join_pay_type

DROP TABLE ta_join_pay_type;

CREATE TABLE ta_join_pay_type
(
  "PayTypeID" character varying(6) NOT NULL,
  "PayTypeName" character varying(100),
  "PayTypeDesc" character varying(200),
  CONSTRAINT ta_join_pay_type_pkey PRIMARY KEY ("PayTypeID" )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ta_join_pay_type
  OWNER TO dev;
-- Table: ta_join_payment

DROP TABLE ta_join_payment;

CREATE TABLE ta_join_payment
(
  id serial NOT NULL,
  id_main integer, -- รหัส join Main
  ta_join_payment_id character varying(255), -- รหัสการชำระค่าเข้าร่วม
  car_license character varying(255), -- เลขทะเบียนรถ
  car_license_seq smallint NOT NULL DEFAULT (0)::smallint, -- ลำดับการโอน ขายคืน รถยึด ฯ  /1 /2
  idno character varying(255), -- รหัสสัญญา
  cpro_name character varying(255), -- ชื่อ-นามสกุลลูกค้า
  start_pay_date date, -- เดือนที่เริ่มเก็บค่าเข้าร่วม
  pay_date date, -- วันที่ชำระ
  pay_type smallint NOT NULL DEFAULT (0)::smallint, -- ประเภทการชำระ 0 =300/เดือน 1= 100/เดือน
  amount numeric, -- จำนวนเงิน
  amount_balance numeric NOT NULL DEFAULT 0.00, -- จำนวนเงินที่ชำระทั้งหมด amount_net - Vat
  amount_net numeric NOT NULL DEFAULT 0.00, -- จำนวนเงินที่ชำระทั้งหมด (รวม ค่าชำระหนี้ ค่าแรกเข้า5000 ค่าชำระล่วงหน้า ถ้ามี)
  amount_discount numeric NOT NULL DEFAULT 0.00, -- ส่วนลดพิเศษ
  vat_percent numeric NOT NULL DEFAULT 0.00, -- %Vat
  amount_vat numeric NOT NULL DEFAULT 0.00,
  amount_wh_tax numeric NOT NULL DEFAULT 0.00,
  cash_note character varying(255),
  transfer_note character varying(255),
  cheque_note character varying(255),
  cs_cheque_note character varying(255),
  update_m_note character varying(255),
  discount_note character varying(255),
  tax_wh_note character varying(255), -- หมายเหตุ ภาษีหัก ณ ที่จ่าย
  status_tax_wh smallint NOT NULL DEFAULT (0)::smallint, -- สถานะภาษีหัก ณ ที่จ่าย
  user_tax_wh character varying(255), -- ผู้ทำรายการ tax_wh
  amount_month integer, -- จำนวนเดือนที่จ่ายค่าเข้าร่วม
  period_date date, -- วันหมดอายุค่าเข้าร่วมครั้งที่แล้ว
  expire_date date, -- วันหมดอายุค่าเข้าร่วม
  pay character varying(255), -- รูปแบบการชำระ
  deduct_fin smallint NOT NULL DEFAULT (0)::smallint, -- หักเป็นเงินสดจากยอดจัดไฟแนนซ์หรือไม่
  pay_ar numeric DEFAULT 0.00, -- จ่ายค่าค้างชำระ(แต่ยังไม่ครบ)
  note text, -- หมายเหตุ
  change_pay_type smallint NOT NULL DEFAULT (0)::smallint, -- ค่าเข้าร่วม
  payment_image smallint NOT NULL DEFAULT (0)::smallint, -- มีรูปเอกสารการชำระหรือไม่?
  create_datetime timestamp without time zone, -- วันเวลาที่สร้างรายการ
  create_by character varying(255), -- พนักงานที่สร้างรายการ
  update_datetime timestamp without time zone, -- วันเวลาที่แก้ไขรายการ
  update_by character varying(255), -- พนักงานที่แก้ไขรายการ
  deleted smallint NOT NULL DEFAULT (0)::smallint, -- ลบแล้วหรือไม่?
  CONSTRAINT ta_join_payment_pkey PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ta_join_payment
  OWNER TO dev;
COMMENT ON COLUMN ta_join_payment.id_main IS 'รหัส join Main';
COMMENT ON COLUMN ta_join_payment.ta_join_payment_id IS 'รหัสการชำระค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment.car_license IS 'เลขทะเบียนรถ';
COMMENT ON COLUMN ta_join_payment.car_license_seq IS 'ลำดับการโอน ขายคืน รถยึด ฯ  /1 /2';
COMMENT ON COLUMN ta_join_payment.idno IS 'รหัสสัญญา';
COMMENT ON COLUMN ta_join_payment.cpro_name IS 'ชื่อ-นามสกุลลูกค้า';
COMMENT ON COLUMN ta_join_payment.start_pay_date IS 'เดือนที่เริ่มเก็บค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment.pay_date IS 'วันที่ชำระ';
COMMENT ON COLUMN ta_join_payment.pay_type IS 'ประเภทการชำระ 0 =300/เดือน 1= 100/เดือน';
COMMENT ON COLUMN ta_join_payment.amount IS 'จำนวนเงิน';
COMMENT ON COLUMN ta_join_payment.amount_balance IS 'จำนวนเงินที่ชำระทั้งหมด amount_net - Vat';
COMMENT ON COLUMN ta_join_payment.amount_net IS 'จำนวนเงินที่ชำระทั้งหมด (รวม ค่าชำระหนี้ ค่าแรกเข้า5000 ค่าชำระล่วงหน้า ถ้ามี)';
COMMENT ON COLUMN ta_join_payment.amount_discount IS 'ส่วนลดพิเศษ';
COMMENT ON COLUMN ta_join_payment.vat_percent IS '%Vat';
COMMENT ON COLUMN ta_join_payment.tax_wh_note IS 'หมายเหตุ ภาษีหัก ณ ที่จ่าย';
COMMENT ON COLUMN ta_join_payment.status_tax_wh IS 'สถานะภาษีหัก ณ ที่จ่าย';
COMMENT ON COLUMN ta_join_payment.user_tax_wh IS 'ผู้ทำรายการ tax_wh';
COMMENT ON COLUMN ta_join_payment.amount_month IS 'จำนวนเดือนที่จ่ายค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment.period_date IS 'วันหมดอายุค่าเข้าร่วมครั้งที่แล้ว';
COMMENT ON COLUMN ta_join_payment.expire_date IS 'วันหมดอายุค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment.pay IS 'รูปแบบการชำระ';
COMMENT ON COLUMN ta_join_payment.deduct_fin IS 'หักเป็นเงินสดจากยอดจัดไฟแนนซ์หรือไม่';
COMMENT ON COLUMN ta_join_payment.pay_ar IS 'จ่ายค่าค้างชำระ(แต่ยังไม่ครบ)';
COMMENT ON COLUMN ta_join_payment.note IS 'หมายเหตุ';
COMMENT ON COLUMN ta_join_payment.change_pay_type IS 'ค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment.payment_image IS 'มีรูปเอกสารการชำระหรือไม่?';
COMMENT ON COLUMN ta_join_payment.create_datetime IS 'วันเวลาที่สร้างรายการ';
COMMENT ON COLUMN ta_join_payment.create_by IS 'พนักงานที่สร้างรายการ';
COMMENT ON COLUMN ta_join_payment.update_datetime IS 'วันเวลาที่แก้ไขรายการ';
COMMENT ON COLUMN ta_join_payment.update_by IS 'พนักงานที่แก้ไขรายการ';
COMMENT ON COLUMN ta_join_payment.deleted IS 'ลบแล้วหรือไม่?';


-- Index: car_license3

DROP INDEX car_license3;

CREATE INDEX car_license3
  ON ta_join_payment
  USING btree
  (car_license COLLATE pg_catalog."default" );

-- Index: idno3

DROP INDEX idno3;

CREATE INDEX idno3
  ON ta_join_payment
  USING btree
  (idno COLLATE pg_catalog."default" );

-- Index: ta_join_payment_id

DROP INDEX ta_join_payment_id;

CREATE INDEX ta_join_payment_id
  ON ta_join_payment
  USING btree
  (ta_join_payment_id COLLATE pg_catalog."default" );

-- Table: ta_join_payment_bin

DROP TABLE ta_join_payment_bin;

CREATE TABLE ta_join_payment_bin
(
  id serial NOT NULL,
  id_main integer, -- รหัส join Main
  ta_join_payment_id character varying(255), -- รหัสการชำระค่าเข้าร่วม
  car_license character varying(255), -- เลขทะเบียนรถ
  car_license_seq smallint NOT NULL DEFAULT (0)::smallint, -- ลำดับการโอน ขายคืน รถยึด ฯ  /1 /2
  idno character varying(255), -- รหัสสัญญา
  cpro_name character varying(255), -- ชื่อ-นามสกุลลูกค้า
  start_pay_date date, -- เดือนที่เริ่มเก็บค่าเข้าร่วม
  pay_date date, -- วันที่ชำระ
  pay_type smallint NOT NULL DEFAULT (0)::smallint, -- ประเภทการชำระ 0 =300/เดือน 1= 100/เดือน
  amount numeric, -- จำนวนเงิน
  amount_balance numeric NOT NULL DEFAULT 0.00, -- จำนวนเงินที่ชำระทั้งหมด amount_net - Vat
  amount_net numeric NOT NULL DEFAULT 0.00, -- จำนวนเงินที่ชำระทั้งหมด (รวม ค่าชำระหนี้ ค่าแรกเข้า5000 ค่าชำระล่วงหน้า ถ้ามี)
  amount_discount numeric NOT NULL DEFAULT 0.00, -- ส่วนลดพิเศษ
  vat_percent numeric NOT NULL DEFAULT 0.00, -- %Vat
  amount_vat numeric NOT NULL DEFAULT 0.00,
  amount_wh_tax numeric NOT NULL DEFAULT 0.00,
  cash_note character varying(255),
  transfer_note character varying(255),
  cheque_note character varying(255),
  cs_cheque_note character varying(255),
  update_m_note character varying(255),
  discount_note character varying(255),
  tax_wh_note character varying(255), -- หมายเหตุ ภาษีหัก ณ ที่จ่าย
  status_tax_wh smallint NOT NULL DEFAULT (0)::smallint, -- สถานะภาษีหัก ณ ที่จ่าย
  user_tax_wh character varying(255), -- ผู้ทำรายการ
  amount_month integer, -- จำนวนเดือนที่จ่ายค่าเข้าร่วม
  period_date date, -- วันหมดอายุค่าเข้าร่วมครั้งที่แล้ว
  expire_date date, -- วันหมดอายุค่าเข้าร่วม
  pay character varying(255), -- รูปแบบการชำระ
  deduct_fin smallint NOT NULL DEFAULT (0)::smallint, -- หักเป็นเงินสดจากยอดจัดไฟแนนซ์หรือไม่
  pay_ar numeric DEFAULT 0.00, -- จ่ายค่าค้างชำระ(แต่ยังไม่ครบ)
  note text, -- หมายเหตุ
  change_pay_type smallint NOT NULL DEFAULT (0)::smallint, -- ค่าเข้าร่วม
  payment_image smallint NOT NULL DEFAULT (0)::smallint, -- มีรูปเอกสารการชำระหรือไม่?
  create_datetime timestamp without time zone, -- วันเวลาที่สร้างรายการ
  create_by character varying(255), -- พนักงานที่สร้างรายการ
  update_datetime timestamp without time zone, -- วันเวลาที่แก้ไขรายการ
  update_by character varying(255), -- พนักงานที่แก้ไขรายการ
  deleted smallint NOT NULL DEFAULT (0)::smallint -- ลบแล้วหรือไม่?
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ta_join_payment_bin
  OWNER TO dev;
COMMENT ON COLUMN ta_join_payment_bin.id_main IS 'รหัส join Main';
COMMENT ON COLUMN ta_join_payment_bin.ta_join_payment_id IS 'รหัสการชำระค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment_bin.car_license IS 'เลขทะเบียนรถ';
COMMENT ON COLUMN ta_join_payment_bin.car_license_seq IS 'ลำดับการโอน ขายคืน รถยึด ฯ  /1 /2';
COMMENT ON COLUMN ta_join_payment_bin.idno IS 'รหัสสัญญา';
COMMENT ON COLUMN ta_join_payment_bin.cpro_name IS 'ชื่อ-นามสกุลลูกค้า';
COMMENT ON COLUMN ta_join_payment_bin.start_pay_date IS 'เดือนที่เริ่มเก็บค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment_bin.pay_date IS 'วันที่ชำระ';
COMMENT ON COLUMN ta_join_payment_bin.pay_type IS 'ประเภทการชำระ 0 =300/เดือน 1= 100/เดือน';
COMMENT ON COLUMN ta_join_payment_bin.amount IS 'จำนวนเงิน';
COMMENT ON COLUMN ta_join_payment_bin.amount_balance IS 'จำนวนเงินที่ชำระทั้งหมด amount_net - Vat';
COMMENT ON COLUMN ta_join_payment_bin.amount_net IS 'จำนวนเงินที่ชำระทั้งหมด (รวม ค่าชำระหนี้ ค่าแรกเข้า5000 ค่าชำระล่วงหน้า ถ้ามี)';
COMMENT ON COLUMN ta_join_payment_bin.amount_discount IS 'ส่วนลดพิเศษ';
COMMENT ON COLUMN ta_join_payment_bin.vat_percent IS '%Vat';
COMMENT ON COLUMN ta_join_payment_bin.tax_wh_note IS 'หมายเหตุ ภาษีหัก ณ ที่จ่าย';
COMMENT ON COLUMN ta_join_payment_bin.status_tax_wh IS 'สถานะภาษีหัก ณ ที่จ่าย';
COMMENT ON COLUMN ta_join_payment_bin.user_tax_wh IS 'ผู้ทำรายการ';
COMMENT ON COLUMN ta_join_payment_bin.amount_month IS 'จำนวนเดือนที่จ่ายค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment_bin.period_date IS 'วันหมดอายุค่าเข้าร่วมครั้งที่แล้ว';
COMMENT ON COLUMN ta_join_payment_bin.expire_date IS 'วันหมดอายุค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment_bin.pay IS 'รูปแบบการชำระ';
COMMENT ON COLUMN ta_join_payment_bin.deduct_fin IS 'หักเป็นเงินสดจากยอดจัดไฟแนนซ์หรือไม่';
COMMENT ON COLUMN ta_join_payment_bin.pay_ar IS 'จ่ายค่าค้างชำระ(แต่ยังไม่ครบ)';
COMMENT ON COLUMN ta_join_payment_bin.note IS 'หมายเหตุ';
COMMENT ON COLUMN ta_join_payment_bin.change_pay_type IS 'ค่าเข้าร่วม';
COMMENT ON COLUMN ta_join_payment_bin.payment_image IS 'มีรูปเอกสารการชำระหรือไม่?';
COMMENT ON COLUMN ta_join_payment_bin.create_datetime IS 'วันเวลาที่สร้างรายการ';
COMMENT ON COLUMN ta_join_payment_bin.create_by IS 'พนักงานที่สร้างรายการ';
COMMENT ON COLUMN ta_join_payment_bin.update_datetime IS 'วันเวลาที่แก้ไขรายการ';
COMMENT ON COLUMN ta_join_payment_bin.update_by IS 'พนักงานที่แก้ไขรายการ';
COMMENT ON COLUMN ta_join_payment_bin.deleted IS 'ลบแล้วหรือไม่?';


-- Index: car_license4

DROP INDEX car_license4;

CREATE INDEX car_license4
  ON ta_join_payment_bin
  USING btree
  (car_license COLLATE pg_catalog."default" );

-- Index: idno4

DROP INDEX idno4;

CREATE INDEX idno4
  ON ta_join_payment_bin
  USING btree
  (idno COLLATE pg_catalog."default" );

-- Index: ta_join_payment_id4

DROP INDEX ta_join_payment_id4;

CREATE INDEX ta_join_payment_id4
  ON ta_join_payment_bin
  USING btree
  (ta_join_payment_id COLLATE pg_catalog."default" );

-- Table: sys_current_rate

DROP TABLE sys_current_rate;

CREATE TABLE sys_current_rate
(
  current_rate_id integer NOT NULL,
  current_rate_name character varying(255) NOT NULL,
  current_rate_des character varying(255) NOT NULL,
  current_rate_base character varying(255) NOT NULL,
  current_rate_value character varying(255) NOT NULL,
  current_rate_note text NOT NULL, -- คำอธิบาย
  CONSTRAINT sys_current_rate_pkey PRIMARY KEY (current_rate_id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE sys_current_rate
  OWNER TO dev;
COMMENT ON COLUMN sys_current_rate.current_rate_note IS 'คำอธิบาย';

INSERT INTO ta_join_pay_type ("PayTypeID", "PayTypeName", "PayTypeDesc") VALUES ('NV', 'เงินรับชั่วคราว', 'รับชำระผ่านหน้ารับชำระชั่วคราว');
INSERT INTO ta_join_pay_type ("PayTypeID", "PayTypeName", "PayTypeDesc") VALUES ('DP', 'เงินรับฝาก', 'รับชำระจากเงินรับฝาก');
INSERT INTO ta_join_pay_type ("PayTypeID", "PayTypeName", "PayTypeDesc") VALUES ('TR', 'เงินโอน', 'รับชำระจากเงินโอน ');
INSERT INTO ta_join_pay_type ("PayTypeID", "PayTypeName", "PayTypeDesc") VALUES ('BL', 'Bill Payment', 'Bill payment');
INSERT INTO ta_join_pay_type ("PayTypeID", "PayTypeName", "PayTypeDesc") VALUES ('CA', 'เงินสด', 'รับชำระเงินสด');
INSERT INTO ta_join_pay_type ("PayTypeID", "PayTypeName", "PayTypeDesc") VALUES ('CQ', 'เช็คธนาคาร', 'รับชำระโดยเช็ค');
INSERT INTO ta_join_pay_type ("PayTypeID", "PayTypeName", "PayTypeDesc") VALUES ('UD', 'เงินปรับปรุง', 'เงินปรับปรุงระบบเก่า');



INSERT INTO sys_current_rate (current_rate_id, current_rate_name, current_rate_des, current_rate_base, current_rate_value) VALUES (1, 'vat_rate', 'vat_rate', 'vat_rate', '7' );
INSERT INTO sys_current_rate (current_rate_id, current_rate_name, current_rate_des, current_rate_base, current_rate_value) VALUES (2, 'wh_tax_rate', 'wh_tax_rate', 'wh_tax_rate', '3');

