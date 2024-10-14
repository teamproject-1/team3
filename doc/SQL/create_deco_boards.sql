DROP TABLE IF EXISTS deco_boards;

CREATE TABLE deco_boards (
	deco_id				BIGINT(20) UNSIGNED 	PRIMARY KEY AUTO_INCREMENT
	,cal_id				BIGINT(20) UNSIGNED 	NOT NULL
	,weather				VARCHAR(1000)
	,emotion				VARCHAR(1000)
	,theme				CHAR(1)					DEFAULT '3'		COMMENT '0이면 동물, 1이면 식물, 2면 픽셀, 3은 없음'
);

ALTER TABLE deco_boards
ADD CONSTRAINT fk_deco_cal_id
FOREIGN KEY (cal_id)
REFERENCES calendar_boards(cal_id);