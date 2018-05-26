
--
-- Table structure for table inbound/Download statistics
--

create table inbound_%m%Y (
        agent_id INT(4) UNSIGNED NOT NULL,
        mac_src CHAR(17) NOT NULL,
        mac_dst CHAR(17) NOT NULL,
        vlan INT(2) UNSIGNED NOT NULL,
        ip_src CHAR(15) NOT NULL,
        ip_dst CHAR(15) NOT NULL,
        src_port INT(2) UNSIGNED NOT NULL,
        dst_port INT(2) UNSIGNED NOT NULL,
        ip_proto CHAR(6) NOT NULL,
        packets INT UNSIGNED NOT NULL,
        bytes BIGINT UNSIGNED NOT NULL,
        stamp_inserted DATETIME NOT NULL,
        stamp_updated DATETIME,
        PRIMARY KEY (agent_id, mac_src, mac_dst, vlan, ip_src, ip_dst, src_port, dst_port, ip_proto, stamp_inserted)
);
-- --------------------------------------------------------

--
-- Table structure for outbound/upload statistics
--

create table outbound_%m%Y (
        agent_id INT(4) UNSIGNED NOT NULL,
        mac_src CHAR(17) NOT NULL,
        mac_dst CHAR(17) NOT NULL,
        vlan INT(2) UNSIGNED NOT NULL,
        ip_src CHAR(15) NOT NULL,
        ip_dst CHAR(15) NOT NULL,
        src_port INT(2) UNSIGNED NOT NULL,
        dst_port INT(2) UNSIGNED NOT NULL,
        ip_proto CHAR(6) NOT NULL,
        packets INT UNSIGNED NOT NULL,
        bytes BIGINT UNSIGNED NOT NULL,
        stamp_inserted DATETIME NOT NULL,
        stamp_updated DATETIME,
        PRIMARY KEY (agent_id, mac_src, mac_dst, vlan, ip_src, ip_dst, src_port, dst_port, ip_proto, stamp_inserted)
);-----------------------------------------

