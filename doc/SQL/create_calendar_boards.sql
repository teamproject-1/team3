DROP TABLE IF EXISTS calendar_boards;

CREATE TABLE calendar_boards (
	cal_id 				BIGINT(20) UNSIGNED 	PRIMARY KEY AUTO_INCREMENT
	,year 				INT(5) 					NOT NULL
	,month 				INT(5) 					NOT NULL
	,day 					INT(5) 					NOT NULL
	,weather				VARCHAR(1000)
	,emotion				VARCHAR(1000)
	,theme				CHAR(1)					DEFAULT '3'		COMMENT '0이면 동물, 1이면 식물, 2면 픽셀, 3은 없음'
	,created_at 	TIMESTAMP				NOT NULL	DEFAULT CURRENT_TIMESTAMP()
	,updated_at 	TIMESTAMP				NOT NULL	DEFAULT CURRENT_TIMESTAMP()
	,deleted_at 	TIMESTAMP
);


	,memo_content		VARCHAR(500)
	,memo_created_at 	TIMESTAMP 				DEFAULT CURRENT_TIMESTAMP()
	,memo_updated_at 	TIMESTAMP 				DEFAULT CURRENT_TIMESTAMP()
	,memo_deleted_at 	TIMESTAMP