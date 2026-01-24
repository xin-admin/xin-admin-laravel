import createAxios from "@/utils/request";

interface WebInfo {
    title: string;
    subtitle: string;
    describe: string;
    logo: string;
}

export const getWebInfo = () => {
    return createAxios<WebInfo>({
        url: "/index",
        method: "get",
    });
};
