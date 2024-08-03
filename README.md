# SmartEarningCall
fine-tuning earning call summary

Current Pain Points for retail investors:
1. Search latest full earning call transcript are expensive from seeking alpha, the Motley Fool and yahoo finance. plus often times you can't even find every company you want.
2. The full earning call transcript is not readable, too long, not organized, investors only wants to spend 5 mins to read the key information. (Only professional analyst know how to extract the key info from earning calls.)
3. The purpose of earning call is to validate the information from earning report and have further elaboration from management team. The earning call transcript can't be used to do sentiment analysis. it is totally wrong direction. We use ChatGPT to consolidate current quarter's earning call transcript with previous quarters' and current quarter's earning report to answer investor's questions and generate full picture of company's operation and forward looking statement from management.

Problem Solution:
Use ChatGPT to answer investors and analysts' questions from earning call and validate management's answers' possibility.

Proceed steps:
1. identify the source types of earning call for all public listing companies; (Youtube, Websites, Video/Audio)
2. Use python library to link to earning call (Youtube channel, like Tesla)
3. Use python command to read/extract (organziely) contents from Youtube to Text.
4. Upload company's last 4 quarters earning report and last quarters earning call full transcript into ChatGPT knowledge
5. Use API connect to ChatGPT, read knowledge
6. Collect investors and analyst questions from earning calls, forum, social medias
7. Create prompts and list out all promopt with answers.
