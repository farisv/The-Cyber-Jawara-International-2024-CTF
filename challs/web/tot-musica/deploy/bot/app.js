import puppeteer from 'puppeteer'
import express from 'express'
import rateLimit from 'express-rate-limit'

const app = express()
app.use(express.json())
app.use(
    '/visit', rateLimit({
        windowMs: 5 * 60 * 1000, // 5 Minutes
        max: 3,
        message: { error: 'Too many requests, try again later' }
    })
)

const port = process.env.PORT || 8000
const APP_URL = process.env.APP_URL || 'http://nginx/'
const FLAG = process.env.FLAG || "fakeflag"
const sleep = ms => new Promise(r => setTimeout(r, ms));

console.log(`FLAG: ${FLAG}`)

async function visit(url) {
    let browser = await puppeteer.launch({
        pipe: true,
        dumpio: true,
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-background-networking',
            '--disable-default-apps',
            '--disable-extensions',
            '--disable-gpu',
            '--disable-sync',
            '--disable-translate',
            '--hide-scrollbars',
            '--metrics-recording-only',
            '--mute-audio',
            '--no-first-run',
            '--safebrowsing-disable-auto-update',
            '--disable-dev-shm-usage',
            '--incognito',
        ]
    })
    const ctx = await browser.createBrowserContext()
    const page = await ctx.newPage()
    try {
        await page.goto(`${APP_URL}login`, { timeout: 3 * 1000, waitUntil: 'networkidle2' })
        await page.waitForSelector('input[name=username]');
        await page.type('input[name=username]', 'uta');
        await page.waitForSelector('input[name=password]');
        await page.type('input[name=password]', FLAG);

        await page.click('#submit');

        await sleep(3*1000)

        await page.goto(url);

        // 5 Minutes
        await sleep(5*60*1000);

    } catch (err){
        console.log(err);
    } finally {
        await page.close()
        await ctx.close()
    }
}

app.get('/visit', async (req, res) => {
    let {url} = req.query
    if(
        (typeof url !== 'string') || (url === undefined) ||
        (url === '') || (!url.startsWith('http'))
    ){
        return res.status(400).send({error: "Invalid url"})
    }

    try {
        console.log(`[*] Visiting -> ${url}`)
        await visit(url)
        console.log(`[*] Done visiting -> ${url}`)
        return res.sendStatus(200)
    } catch (e) {
        console.error(`[-] Error visiting -> ${url}: ${e.message}`)
        return res.status(400).send({ error: e.message })
    }
})

app.listen(port, async () => {
    console.log(`[*] Listening on port ${port}`)
})