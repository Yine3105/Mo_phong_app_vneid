-- Bổ sung loại yêu cầu báo mất thẻ để tách luồng Công dân - Cán bộ.
ALTER TABLE yeu_cau_cap_the
MODIFY COLUMN loai_yeu_cau
ENUM('cap_moi','cap_doi','cap_lai','bao_mat')
NOT NULL;
