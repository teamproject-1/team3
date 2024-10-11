DROP TABLE IF EXISTS deco_boards;

CREATE TABLE deco_boards (
	deco_id				BIGINT(20) UNSIGNED 	PRIMARY KEY AUTO_INCREMENT
	,cal_id				BIGINT(20) UNSIGNED 	NOT NULL
	,weather				VARCHAR(1000)
	,emotion				VARCHAR(1000)
	,theme_tape			VARCHAR(1000)
	,theme_sticker1	VARCHAR(1000)
	,theme_sticker2	VARCHAR(1000)
	,theme_sticker3	VARCHAR(1000)
);

ALTER TABLE deco_boards
ADD CONSTRAINT fk_deco_cal_id
FOREIGN KEY (cal_id)
REFERENCES calendar_boards(cal_id);