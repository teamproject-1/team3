DROP TABLE IF EXISTS todolist_boards;

CREATE TABLE todolist_boards (
	td_id					BIGINT(20) UNSIGNED 	PRIMARY KEY AUTO_INCREMENT
	,cal_id				BIGINT(20) UNSIGNED	NOT NULL
	,content				VARCHAR(100) 			NOT NULL 
	,check_todo			INT(1) 					NOT NULL 	DEFAULT 0 	COMMENT '1이면 수행, 0이면 수행X'
	,todo_created_at 	TIMESTAMP 				NOT NULL		DEFAULT CURRENT_TIMESTAMP()
	,todo_updated_at 	TIMESTAMP 				NOT NULL 	DEFAULT CURRENT_TIMESTAMP()
	,todo_deleted_at 	TIMESTAMP
);

ALTER TABLE todolist_boards
ADD CONSTRAINT fk_todolist_cal_id
FOREIGN KEY (cal_id)
REFERENCES calendar_boards(cal_id);