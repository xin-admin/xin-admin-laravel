import {Card, Row, Col, Space, Divider, List, Avatar, theme} from "antd";
import {ArrowDownOutlined, ArrowUpOutlined} from "@ant-design/icons";
import { useTranslation } from 'react-i18next';
const {useToken} = theme

const Monitor = () => {
  const {token} = useToken();
  const { t } = useTranslation();
  const data = [
    {
      title: '3号楼2层走廊灯不亮，需检查线路并更换灯泡。',
      description: '3号楼2层走廊灯故障，经检查为灯泡损坏，需更换LED节能灯泡（型号：E27-9W），并排查线路是否老化。预计完成时间：6月20日前。',
    },
    {
      title: '安排维保单位对小区所有电梯进行月度维护，确保运行安全。',
      description: '联系XX维保公司（电话：123-456789）于6月25日对小区12部电梯进行月度维护，重点检查曳引机、门锁装置及应急报警功能，完成后提交保养报告。',
    },
    {
      title: '5号楼前绿化带杂草丛生，需安排园艺工人本周内清理。',
      description: '5号楼前绿化带因雨季杂草生长迅速，需安排园艺组（责任人：张师傅）本周内清理，并喷洒除草剂。同步检查灌溉系统是否漏水。',
    },
    {
      title: '8号楼302室反映厨房水管漏水，需上门检修并更换配件。',
      description: '8号楼302室业主反映厨房下水管接口渗水，已临时关闭水阀。需工程部带PVC管件（Φ50mm）及密封胶上门维修，完成后拍照反馈业主。',
    },
    {
      title: '检查各楼栋灭火器压力及有效期，记录并更换不合格器材。',
      description: "按消防规定，6月需全面检查56处灭火器压力表（绿色达标）、软管龟裂情况，并更换2号楼过期灭火器（编号：2023-015至018）。",
    },
    {
      title: '安排保洁人员彻底清扫B1层车库，清理杂物和积水。',
      description: "B1层车库地面油渍、垃圾较多，安排保洁组6月22日集中清扫，重点清理排水沟杂物，并检查照明设施是否正常。",
    },
    {
      title: '联系技术员升级小区门禁系统，测试各单元刷卡功能。',
      description: "新门禁系统（版本V2.3）需于6月28日前完成升级，联系供应商调试人脸识别功能，并同步更新业主卡数据。测试期3天。",
    },
    {
      title: '统计未缴费业主名单，发送短信及书面催缴通知。',
      description: "截至6月，尚有15户未缴纳Q2物业费（名单见附件），本周内发送短信提醒，对逾期户上门送达《催缴通知书》（模板编号：WY-2024-06）。",
    }
  ];

  return (
    <Row gutter={[20, 20]}>
      <Col xxl={18} lg={16} xs={24}>
        <Card variant={"borderless"} style={{marginBottom: 20}}>
          <Row gutter={[20, 20]}>
            <Col xxl={6} lg={12} xs={24}>
              <div className={"flex items-center"}>
                <div className={"size-14 rounded-full p-3"} style={{background: token.colorPrimaryBg }}>
                  <img src="/帮办代办.png" alt="1"/>
                </div>
                <div className={"ml-5"}>
                  <div style={{color: token.colorTextDescription}}>{t('dashboard.monitor.helpService')}</div>
                  <div className={"text-[30px]"}>30 / 255</div>
                  <div>{t('dashboard.completion')} <span style={{color: token.colorError}}>11.60%</span></div>
                </div>
              </div>
            </Col>
            <Col xxl={6} lg={12} xs={24}>
              <div className={"flex items-center"}>
                <div className={"size-14 rounded-full p-3"} style={{background: token.colorPrimaryBg }}>
                  <img src="/购房.png" alt="1"/>
                </div>
                <div className={"ml-5"}>
                  <div style={{color: token.colorTextDescription}}>{t('dashboard.monitor.propertyFee')}</div>
                  <div className={"text-[30px]"}>19,308 ￥</div>
                  <div>{t('dashboard.vs.lastWeek')} <span style={{color: token.colorError}}><ArrowUpOutlined />19.20%</span></div>
                </div>
              </div>
            </Col>
            <Col xxl={6} lg={12} xs={24}>
              <div className={"flex items-center"}>
                <div className={"size-14 rounded-full p-3"} style={{background: token.colorPrimaryBg }}>
                  <img src="/报警.png" alt="1"/>
                </div>
                <div className={"ml-5"}>
                  <div style={{color: token.colorTextDescription}}>{t('dashboard.monitor.alarmEvents')}</div>
                  <div className={"text-[30px]"}>30</div>
                  <div>{t('dashboard.vs.lastWeek')} <span style={{color: token.colorSuccess}}><ArrowDownOutlined />32.60%</span></div>
                </div>
              </div>
            </Col>
            <Col xxl={6} lg={12} xs={24}>
              <div className={"flex items-center"}>
                <div className={"size-14 rounded-full p-3"} style={{background: token.colorPrimaryBg }}>
                  <img src="/更多服务.png" alt="1"/>
                </div>
                <div className={"ml-5"}>
                  <div style={{color: token.colorTextDescription}}>{t('dashboard.monitor.otherEvents')}</div>
                  <div className={"text-[30px]"}>255件</div>
                  <div>{t('dashboard.vs.lastWeek')} <span style={{color: token.colorError}}><ArrowUpOutlined />16.50%</span></div>
                </div>
              </div>
            </Col>
          </Row>
        </Card>
        <Card variant={"borderless"}>
          <div style={{ marginBottom: 20, fontSize: token.fontSizeLG, fontWeight: token.fontWeightStrong }}>
            {t('dashboard.monitor.todoList')} <Divider type="vertical" /> <span style={{color: token.colorTextDescription}}>{t('dashboard.monitor.myFocus')}</span>
          </div>
          <Space className={"mb-5"} wrap>
            <div className={"rounded-xl pl-3 pr-3"} style={{ color: token.colorPrimary, background: token.colorPrimaryBg }}>{t('dashboard.monitor.all')} 15</div>
            <div className={"rounded-xl pl-3 pr-3"} style={{ color: token.colorText, background: token.colorBorderSecondary	 }}>{t('dashboard.monitor.propertyOrder')} 8</div>
            <div className={"rounded-xl pl-3 pr-3"} style={{ color: token.colorText, background: token.colorBorderSecondary	 }}>{t('dashboard.monitor.repairOrder')} 2</div>
            <div className={"rounded-xl pl-3 pr-3"} style={{ color: token.colorText, background: token.colorBorderSecondary	 }}>{t('dashboard.monitor.fireSafety')} 3</div>
            <div className={"rounded-xl pl-3 pr-3"} style={{ color: token.colorText, background: token.colorBorderSecondary	 }}>{t('dashboard.monitor.paidService')} 5</div>
            <div className={"rounded-xl pl-3 pr-3"} style={{ color: token.colorText, background: token.colorBorderSecondary	 }}>{t('dashboard.monitor.publicService')} 7</div>
          </Space>
          <List
            itemLayout="horizontal"
            dataSource={data}
            renderItem={(item, index) => (
              <List.Item>
                <List.Item.Meta
                  avatar={<Avatar src={`https://api.dicebear.com/7.x/avataaars/svg?seed=${index}`} />}
                  title={<a>{item.title}</a>}
                  description={item.description}
                />
              </List.Item>
            )}
          />
        </Card>
      </Col>
      <Col xxl={6} lg={8} xs={24}>
        <Card variant={"borderless"} style={{ marginBottom: 20 }}>
          <img src="/group65.png" alt="1"/>
        </Card>
        <Card variant={"borderless"} styles={{body: {padding: 0, borderRadius: token.borderRadius, overflow: 'hidden'}}}>
          <img src="/group57.png" alt="1"/>
        </Card>
      </Col>
    </Row>
  );
}

export default Monitor;