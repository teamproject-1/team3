DROP TABLE IF EXISTS calendar_boards;

CREATE TABLE calendar_boards (
	cal_id 				BIGINT(20) UNSIGNED 	PRIMARY KEY AUTO_INCREMENT
	,year 				INT(5) 					NOT NULL
	,month 				INT(5) 					NOT NULL
	,day 					INT(5) 					NOT NULL
	,memo_content		VARCHAR(500)
	,memo_created_at 	TIMESTAMP 				DEFAULT CURRENT_TIMESTAMP()
	,memo_updated_at 	TIMESTAMP 				DEFAULT CURRENT_TIMESTAMP()
	,memo_deleted_at 	TIMESTAMP
);